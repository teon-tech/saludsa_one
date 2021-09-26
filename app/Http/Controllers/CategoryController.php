<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\ImageParameter;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Multimedia\ImageController;


class CategoryController extends MyBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->layout->content = View::make('category.index', []);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Category::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('category.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $categories = $query
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->full_name,
                    'status' => $item->status
                ];
            })->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $categories
            )
        );
    }

    /**
     * @param null $id
     * @return JsonResponse
     */
    public function getForm($id = null)
    {
        $method = 'POST';
        $category = isset($id) ? Category::find($id) : new Category();
        $categories = Category::query()
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->full_name
                ];
            })
            ->pluck('name', 'id')
            ->toArray();
        $image_parameters = ImageParameter::query()
            ->where('entity', '=', ImageParameter::TYPE_CATEGORY)
            ->get()
            ->toArray();
        foreach ($image_parameters as $idx => $image_parameter) {
            $images = $category->images()
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
        $view = View::make('category.loads._form', [
            'method' => $method,
            'category' => $category,
            'categories' => $categories,
            'image_parameters' => $image_parameters
        ])->render();

        return Response::json(array(
            'html' => $view
        ));
    }


    public function postSave()
    {
        try {
            DB::beginTransaction();
            $data = Request::all();

            if ($data['category_id'] == '') { //Create
                $category = new Category();
                $category->status = Category::STATUS_ACTIVE;
            } else { //Update
                $category = Category::find($data['category_id']);
                $category->status = trim($data['status']);
            }
            $category->name = trim($data['name']);
            $category->level = trim($data['level']);
            if (isset($data['parent_category_id'])) {
                $category->parent_category_id = trim($data['parent_category_id']);
            }
            $category->save();

            $imageController = new ImageController();
            $images = $data['files'] ?? [];
            $params = $data['filesParams'] ?? [];
            $folder = $category->getTable();
            foreach ($images as $index => $file) {
                $folderName = "{$folder}/{$category->id}";
                $auxParams = json_decode($params[$index], true);
                $fileName = $imageController->saveFileAwsS3($file, $folderName);
                $imageModel = new Image();
                $imageModel->file_name = $fileName;
                $imageModel->image_parameter_id = $auxParams['imageParameterId'];
                $category->images()->save($imageModel);
            }

            $deletedMultimediaIds = $data['filesDeleted'] ?? [];
            $deletedMultimedia = Image::query()
                ->whereIn('id', $deletedMultimediaIds)
                ->get();
            foreach ($deletedMultimedia as $itemMultimedia) {
                $path = "{$folder}/{$category->id}/{$itemMultimedia->file_name}";
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
                $itemMultimedia->delete();
            }

            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function postIsNameUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:category,name,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }

}
