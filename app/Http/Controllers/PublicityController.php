<?php

namespace App\Http\Controllers;


use App\Models\Publicity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Http\Controllers\Multimedia\ImageController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class PublicityController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('publicity.index');
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {
        $data = Request::all();
        $query = Publicity::query();
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('publicity.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $records = $query->get()->toArray();
        return Response::json(
            [
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $records,
            ]
        );
    }

    /**
     * @param null $id
     * @return JsonResponse
     */
    public function getForm($id = null)
    {
        $method = 'POST';
        $model = isset($id) && $id ? Publicity::query()->find($id) : new Publicity();

        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_PUBLICITY)
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
        $view = View::make('publicity.loads._form', [
            'method' => $method,
            'modelPublicity' => $model,
            'status_model' => Config::get('constants.status_model'),
            'image_parameters' => $image_parameters
        ])->render();
        return Response::json(array(
            'html' => $view
        ));
    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    public function savePublicity()
    {
        try {
            DB::beginTransaction();

            $data = Request::all();
            $keyIdModel = 'publicity_id';
            if ($data[$keyIdModel] == '') { //Create
                $publicity = new Publicity();
                $publicity->status = Config::get('constants.active_status');
            } else { //Update
                $publicity = Publicity::query()->find($data[$keyIdModel]);
            }
            $publicity->fill($data);
            $publicity->saveOrFail();

            $imageController = new ImageController();
            $images = $data['files'] ?? [];
            $params = $data['filesParams'] ?? [];
            $folder = $publicity->getTable();
            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$publicity->id}";
                $aux_params = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $aux_params['imageParameterId'];
                $publicity->images()->save($imageModel);
            }

            $deletedMultimediaIds = $data['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();
            foreach ($deletedMultimedia as $itemMultimedia) {
                $path = "{$folder}/{$publicity->id}/{$itemMultimedia->file_name}";
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
                $itemMultimedia->delete();
            }

            DB::commit();
            return Response::json([
                'status' => 'success',
                'message' => 'Guardado existoso',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar la publicidad',
                'devMessage' => $e->getMessage()
            ], 500);
        }
    }
}
