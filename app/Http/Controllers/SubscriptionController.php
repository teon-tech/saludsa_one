<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Models\Subscription;


class SubscriptionController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('subscription.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();

        $query = Subscription::query();
        $recordsTotal = $query->get()->count();
        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('subscription.email', 'like', "$search%");
            $query->where('subscription.created_at', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }

        $subscriptions = $query->orderBy('created_at', 'desc')
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'email' => $item->email,
                    'created_at' => $item->created_at->format('Y-m-d h:i')
                ];
            })
            ->toArray();
        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $subscriptions,
            )
        );
    }
}
