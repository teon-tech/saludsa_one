<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\TypePlan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MessageController extends MyBaseController
{

    public function index()
    {
        $method = 'POST';
        $messages = new Message();
        $typePlan = TypePlan::query()
        ->where('status', '=', 'ACTIVE')->get();
        $this->layout->content = View::make('message.index', [
            'method' => $method,
            'typePlan' => $typePlan,
            'messages' => $messages,
        ]);
    }
    public function postSave()
    {
        try {
            $data = Request::all();
            $messages = $data['message'];
            foreach ($messages as $typePlan1 => $rows) {
                foreach ($rows as $typePlan2 => $items) {
                    if ($items != null) {
                        $findMessage = $this->findMessage($typePlan1, $typePlan2);
                        $message = $findMessage ?? new Message();
                        $message->type_plan_id_1 = $typePlan1;
                        $message->type_plan_id_2 = $typePlan2;
                        $message->message = $items;
                        $message->save();
                    }
                }
            }
            return Response::json([
                'status' => 'success',
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'status' => 'error',
                'message' => 'Error al guardar mensajes',
                'devMessage' => $e->getMessage(),
            ], 500);
        }
    }

    public function findMessage($typePlanId1, $typePlanId2)
    {

        $query = Message::query();
        $query->where('type_plan_id_1', '=', $typePlanId1);
        $query->where('type_plan_id_2', '=', $typePlanId2);

        return $query->first();

    }
}
