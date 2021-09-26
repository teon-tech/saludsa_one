<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Multimedia\ImageController;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ProviderController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('provider.index');
    }

    public function view()
    {
        $user = Auth::user();
        $providerId = $user->provider_id;
        if ($providerId != null) {
            $provider = $user->provider;
            $image_parameters = ImageParameter::query()
                ->where('entity', '=', ImageParameter::TYPE_PROVIDER)
                ->get()
                ->toArray();
            foreach ($image_parameters as $idx => $image_parameter) {
                $images = $provider
                    ->images()
                    ->where('image_parameter_id', '=', $image_parameter['id'])
                    ->get();
                $aux_images = [];
                foreach ($images as $image) {
                    $aux_images[] = [
                        'id' => $image->id,
                        'file_name' => $image->file_name,
                        'url' => $image->url
                    ];
                }
                $image_parameters[$idx]['images'] = $aux_images;
            }

            $this->layout->content = View::make('provider.view',
                [
                    'user' => $user,
                    'provider' => $provider,
                    'image_parameters' => $image_parameters,
                ]);
        } else {
            return redirect()->route('indexViewProvider');
        }

    }

    public function getList()
    {
        $data = Request::all();

        $query = Provider::query();

        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('provider.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $providers = $query->get()->toArray();
        return Response::json(
            [
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $providers
            ]
        );
    }

    public function getForm($id = null)
    {
        $model = isset($id) && $id ? Provider::query()->find($id) : new Provider();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_PROVIDER)
            ->get()
            ->toArray();
        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $model->images()
                ->where('image_parameter_id', '=', $image_parameter['id'])
                ->get();
            $aux_images = [];
            foreach ($images as $image) {
                $aux_images[] = [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'url' => $image->url
                ];
            }
            $image_parameters[$idx]['images'] = $aux_images;
        }
        $view = View::make('provider.loads._form', [
                'modelProvider' => $model,
                'image_parameters' => $image_parameters,
            ]
        )->render();

        return Response::json([
            'html' => $view
        ]);
    }

    public function saveProvider()
    {
        try {
            DB::beginTransaction();
            $dataRequest = Request::all();

            $id = $dataRequest['provider_id'];

            $provider = $id ? Provider::query()->find($id) : new Provider();
            $provider->fill($dataRequest);
            $provider->save();

            $imageController = new ImageController();
            $images = $dataRequest['files'] ?? [];
            $params = $dataRequest['filesParams'] ?? [];
            $folder = $provider->getTable();

            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$provider->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $provider->images()->save($imageModel);
            }

            $deletedMultimediaIds = $dataRequest['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();

            foreach ($deletedMultimedia as $itemMultimedia) {
                $path = "{$folder}/{$provider->id}/{$itemMultimedia->file_name}";
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
                $itemMultimedia->delete();
            }
            DB::commit();

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el formulario',
                'devMessage' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProviderUser()
    {
        try {
            DB::beginTransaction();
            $dataRequest = Request::all();
            $providerId = $dataRequest['provider_id'];

            $provider = Provider::query()->find($providerId);
            $provider->name = $dataRequest['provider_name'];
            $provider->owner = $dataRequest['provider_owner'];
            $provider->address = $dataRequest['provider_address'];
            $provider->category = $dataRequest['provider_category'];
            $provider->code = $dataRequest['provider_code'];
            $provider->phone = $dataRequest['provider_phone'];
            $provider->country_code = $dataRequest['provider_country_code'];
            $provider->description = $dataRequest['provider_description'];
            $provider->save();

            $imageController = new ImageController();
            $images = $dataRequest['files'] ?? [];
            $params = $dataRequest['filesParams'] ?? [];
            $folder = $provider->getTable();

            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$provider->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $provider->images()->save($imageModel);
            }

            $deletedMultimediaIds = $dataRequest['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();

            foreach ($deletedMultimedia as $itemMultimedia) {
                $path = "{$folder}/{$provider->id}/{$itemMultimedia->file_name}";
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
                $itemMultimedia->delete();
            }

            //USERS
            $user = Auth::user();
            $user->name = $dataRequest['name'];
            $user->email = $dataRequest['email'];
            if (isset($dataRequest['password']) && $dataRequest['password'] != null || $dataRequest['password'] != '') {
                $user->password = bcrypt($dataRequest['password']);
            }
            $user->save();


            DB::commit();

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el formulario',
                'devMessage' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function postIsCodeUnique()
    {
        $validation = Validator::make(Request::all(), ['code' => 'unique:provider,code,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}