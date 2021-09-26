<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Section;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class SectionController extends MyBaseController
{

    /**
     *
     */
    public function index($id = null)
    {
        $plan = Plan::query()->find($id);
        $this->layout->content = View::make('section.index', [
            'plan' => $plan,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getList($id = null)
    {

        $data = Request::all();

        $query = Section::query();
        $query->with(['plan']);
        $query->where('plan_id', $id);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('sections.title', 'like', "%$search%");
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
        $section = isset($id) ? Section::query()->find($id) : new Section();
        $plans = Plan::query()
            ->where('id', $planId)
            ->first();
        $videosSection = $section->videos() ? $section->videos()->get() : [];

        $view = View::make('section.loads._form', [
            'method' => $method,
            'section' => $section,
            'plans' => $plans,
            'videosSection' => $videosSection,
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
            if ($data['section_id'] == '') { //Create
                $section = new Section();
            } else { //Update
                $section = Section::query()->find($data['section_id']);
            }
            $section->plan_id = $data['plan_id'];
            $section->title = $data['title'];
            $section->description = $data['description'];
            $section->weight = $data['weight'];
            $section->status = $data['status'];
            $section->save();

            Video::query()->where('entity_id', $data['section_id'])->delete();
            $urlVideos = $data['url_element'] ?? [];
            foreach ($urlVideos as $urlVideo) {
                $videoModel = new Video();
                $videoModel->entity_id = $section->id;
                $videoModel->entity_type = $section->table;
                $videoModel->url = $urlVideo;
                if ($videoModel->url != null) {
                    $section->videos()->save($videoModel);
                }
            }

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {

            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar el Section',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    public function deletedSection($id = null)
    {

        Section::query()->find($id)->delete();

        return Response::json(true);

    }

}
