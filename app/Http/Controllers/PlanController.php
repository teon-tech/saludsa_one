<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Multimedia\ImageController;
use App\Models\File;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Models\Plan;
use App\Models\Product;
use App\Models\TypePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Multimedia\FileController;

class PlanController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('plan.index', []);
    }

    /**
     * @return JsonResponse
     */
    public function getList()
    {

        $data = Request::all();

        $query = Plan::query();
        $query->with(['typePlan']);
        $query->with(['product']);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('plan.name', 'like', "%$search%");
            });
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $data = $query->get()->toArray();

        return Response::json(
            [
                'draw' => $data['draw'] ?? null,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
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
        $plan = isset($id) ? Plan::query()->find($id) : new Plan();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_PLAN)
            ->get()
            ->toArray();

        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $plan->images()
                ->where('image_parameter_id', '=', $image_parameter['id'])
                ->get();
            $aux_images = [];
            foreach ($images as $image) {
                $aux_images[] = [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'url' => $image->url,
                ];
            }
            $image_parameters[$idx]['images'] = $aux_images;
        }

        $types = TypePlan::query()
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->pluck('name', 'id')
            ->toArray();
        $products = Product::query()
            ->where('status' , 'ACTIVE')
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->pluck('name', 'id')
            ->toArray();

        $words = $plan->keywords ? explode(',', $plan->keywords) : [];
        $options = [];
        foreach ($words as $word) {
            $options[$word] = $word;
        }
        $filePlans = $plan->files() ? $plan->files()->get()
            : [];
        $auxFilePlan = [];
        foreach ($filePlans as $filePlan) {
            $auxFilePlan[] = [
                'id' => $filePlan->id,
                'file_name' => $filePlan->file_name,
                'url' => $filePlan->url,
            ];
        }
        $view = View::make('plan.loads._form', [
            'method' => $method,
            'plan' => $plan,
            'types' => $types,
            'words' => $words,
            'options' => $options,
            'image_parameters' => $image_parameters,
            'filePlan' => $auxFilePlan,
            'products' => $products

        ])->render();

        return Response::json(array(
            'html' => $view,
        ));

    }

    /**
     * @return JsonResponse
     * @throws \Throwable
     */
    public function postSave()
    {
        try {

            $data = Request::all();
            if ($data['plan_id'] == '') { //Create
                $plan = new Plan();
            } else { //Update
                $plan = Plan::query()->find($data['plan_id']);
            }
            $plan->name = $data['name'];
            $plan->type_plan_id = $data['type_plan_id'];
            $plan->status = $data['status'];
            $plan->default = $data['default'];
            $plan->code = $data['code'];
            $plan->keywords = isset($data['keywords']) ? implode(',', $data['keywords']) : null;
            $plan->color_primary = $data['color_primary'];
            $plan->color_secondary = $data['color_secondary'];
            $plan->description = $data['description'];
            $plan->isComparative = $data['isComparative'];
            $plan->weight = $data['weight'];
            $plan->product_id = $data['product_id'];
            $plan->administrative_price = $data['administrative_price'];
            $plan->farmer_tax = $data['farmer_tax'];

            $plan->save();

            //Imágenes
            $imageController = new ImageController();
            $images = $data['files'] ?? [];
            $params = $data['filesParams'] ?? [];
            $folder = $plan->getTable();
            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$plan->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $plan->images()->save($imageModel);

            }
            $deletedMultimediaIds = $data['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();
            foreach ($deletedMultimedia as $itemMultimedia) {
                if (config('constants.logicFileSystem') == 's3') {
                    $path = "{$folder}/{$plan->id}/{$itemMultimedia->file_name}";
                } else {
                    $path = "uploads/{$folder}/{$plan->id}/{$itemMultimedia->file_name}";
                }
                if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                    Storage::disk(config('constants.logicFileSystem'))->delete($path);
                }
                $itemMultimedia->delete();
            }

            /* Archivos */
            $fileController = new FileController();
            $filesDocs = $data['filesDocs'] ?? [];
            $folder = $plan->getTable();
            foreach ($filesDocs as $index => $file) {
                $folderName = "{$folder}/{$plan->id}";
                $fileName = $fileController->saveFileAwsS3($file, $folderName);
                $fileModel = new File();
                $fileModel->file_name = $fileName;
                $plan->files()->save($fileModel);
            }
            $deletedMultimediaIds = $data['docsDeleted'] ?? [];
            $deletedMultimedia = File::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();

            foreach ($deletedMultimedia as $itemMultimedia) {
                if (config('constants.logicFileSystem') == 's3') {
                    $path = "{$folder}/{$plan->id}/{$itemMultimedia->file_name}";
                } else {
                    $path = "uploads/{$folder}/{$plan->id}/{$itemMultimedia->file_name}";
                }

                if (Storage::disk(config('constants.logicFileSystem'))->exists($path)) {
                    Storage::disk(config('constants.logicFileSystem'))->delete($path);
                }
                $itemMultimedia->delete();
            }

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {

            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el plan',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:plan,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

    /**
     * @return JsonResponse
     */
    public function postIsCodeUnique()
    {
        $validation = Validator::make(Request::all(), ['code' => 'unique:plan,code,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

    /**
     * @param null $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function deletedPlan($id = null)
    {

        Plan::query()->find($id)->delete();

        return Response::json(true);

    }
}
