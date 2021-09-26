<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Multimedia\ImageController;
use App\Models\Coverage;
use App\Models\Image;
use App\Models\ImageParameter;
use App\Models\Plan;
use App\Models\TypeCoverage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class CoverageController extends MyBaseController
{

    /**
     *
     */
    public function index($id = null)
    {
        $plan = Plan::query()->find($id);
        $this->layout->content = View::make('coverage.index', [
            'plan' => $plan,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getList($id = null)
    {
        $data = Request::all();

        $query = Coverage::query();
        $query->with(['typeCoverage']);
        $query->with(['plan']);
        $query->where('plan_id', $id);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('coverage.name', 'like', "%$search%");
            });
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int) $data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int) $data['length']);
        }

        $data = $query->get()->toArray();

        $plan = Plan::query()->find($id);
        return Response::json(
            [
                'draw' => $data['draw'] ?? null,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
                'plan' => $plan,
            ]
        );
    }

    /**
     * @param null $id
     * @return JsonResponse
     */
    public function getForm($planId = null, $id = null)
    {
        $method = 'POST';
        $coverage = isset($id) ? Coverage::query()->find($id) : new Coverage();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_COVERAGE)
            ->get()
            ->toArray();

        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $coverage->images()
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
        
        $plans = Plan::query()
            ->where('id', $planId)
            ->first();

        $types = TypeCoverage::query()
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->pluck('name', 'id')
            ->toArray();
        // dd($types);

        $view = View::make('coverage.loads._form', [
            'method' => $method,
            'types' => $types,
            'coverage' => $coverage,
            'plans' => $plans,
            'image_parameters' => $image_parameters,

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
            if ($data['coverage_id'] == '') { //Create
                $coverage = new Coverage();
            } else { //Update
                $coverage = Coverage::query()->find($data['coverage_id']);
            }
            $coverage->name = $data['name'];
            $coverage->plan_id = $data['plan_id'];
            $coverage->type_coverage_id = $data['type_coverage_id'];
            $coverage->description = $data['description'];
            $coverage->status = $data['status'];
            if(isset($data['isMaternity'])){
            $coverage->isMaternity =  $data['isMaternity'];
            }
            $coverage->save();

            $imageController = new ImageController();
            $images = $data['files'] ?? [];
            $params = $data['filesParams'] ?? [];
            $folder = $coverage->getTable();
            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$coverage->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $coverage->images()->save($imageModel);
            }
            $deletedMultimediaIds = $data['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();

            foreach ($deletedMultimedia as $itemMultimedia) {
                if(config('constants.logicFileSystem') == 's3'){
                    $path = "{$folder}/{$coverage->id}/{$itemMultimedia->file_name}";
                }else{
                    $path = "uploads/{$folder}/{$coverage->id}/{$itemMultimedia->file_name}";
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
                'message' => 'Error al guardar la cobertura',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function postIsCoverageTypeUnique()
    {
        $validation = Validator::make(Request::all(),
            [
                'type_coverage_id' => [
                    Rule::unique('coverage', 'type_coverage_id')
                        ->ignore(Request::get('idPlan'), 'id'),
                ],
            ]
        );
        return Response::json($validation->passes());
    }
    public function deletedCoverage($id = null)
    {

        Coverage::query()->find($id)->delete();

        return Response::json(true);

    }
}
