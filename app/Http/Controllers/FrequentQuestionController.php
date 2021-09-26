<?php

namespace App\Http\Controllers;

use App\Models\FrequentQuestion;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class FrequentQuestionController extends MyBaseController
{

    public function index($id = null)
    {
        $plan = Plan::query()->find($id);
        $this->layout->content = View::make('frequenteQuestion.index', [
            'plan' => $plan,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getList($id = null)
    {
        $data = Request::all();

        $query = FrequentQuestion::query();
        $query->with(['plan']);
        $query->where('plan_id', $id);
        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;
        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('frequent_questions.title', 'like', "%$search%");
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
        $question = isset($id) ? FrequentQuestion::query()->find($id) : new FrequentQuestion();

        $plans = Plan::query()
            ->where('id', $planId)
            ->first();

        $view = View::make('frequenteQuestion.loads._form', [
            'method' => $method,
            'question' => $question,
            'plans' => $plans,

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
            if ($data['question_id'] == '') { //Create
                $question = new FrequentQuestion();
            } else { //Update
                $question = FrequentQuestion::query()->find($data['question_id']);
            }
            $question->title = $data['title'];
            $question->plan_id = $data['plan_id'];
            $question->weight = $data['weight'];
            $question->description = $data['description'];
            $question->status = $data['status'];
            $question->save();

            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {

            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar ',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    public function deletedQuestion($id = null)
    {
        FrequentQuestion::query()->find($id)->delete();
        return Response::json(true);
    }
}
