<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\MyBaseController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Services\SendgridService;

class UserController extends MyBaseController
{

    public function index()
    {
        $this->layout->content = View::make('rbac.users.index', [
        ]);
    }

    public function getList()
    {
        $data = Request::all();
        $query = User::query();

        $recordsTotal = $query->get()->count();

        $recordsFiltered = $recordsTotal;

        if (isset($data['search']['value']) && $data['search']['value']) {
            $search = $data['search']['value'];
            $query->where('users.name', 'like', "$search%");
            $recordsFiltered = $query->get()->count();
        }
        if (isset($data['start']) && $data['start']) {
            $query->offset((int)$data['start']);
        }
        if (isset($data['length']) && $data['length']) {
            $query->limit((int)$data['length']);
        }
        if (isset($data['order']) && $data['order']) {
            $orders = $data['order'];
            foreach ($orders as $order) {
                $column = $order['column'];
                $dir = $order['dir'];
                $column_name = $data['columns'][$column]['data'];
                $query->orderBy('users.' . $column_name, $dir);
            }
        }
        $users = $query->get()->toArray();

        return Response::json(
            array(
                'draw' => $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $users
            )
        );
    }

    public function getForm($id = null)
    {
        $method = 'POST';
        $user = isset($id) ? User::find($id) : new User();
        $roles = Role::all()->pluck('name', 'id')->toArray();
        $view = View::make('rbac.users.loads._form', [
            'method' => $method,
            'user' => $user,
            'roles' => $roles,
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
            $isCreated = false;
            if ($data['user_id'] == '') { //Create
                $user = new User();
                $user->status = 'ACTIVE';
                $isCreated = true;
            } else { //Update
                $user = User::query()->find($data['user_id']);
                if (isset($data['status']))
                    $user->status = $data['status'];
            }
            $user->name = trim($data['name']);
            $user->email = trim($data['email']);
            $user->provider_id = isset($data['provider_id']) && $data['provider_id'] != '' ? trim($data['provider_id']) : null;
            if (isset($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();
            if (isset($data['role'])) {
                $user->syncRoles($data['role']);
            }
            if ($isCreated) {
                $templateId = config('services.sendgrid.user_template_id');
                $dataSend = [
                    'username' => $data['name'],
                    'password' => $data['password'],
                    'subject' => 'Credenciales para el ingreso al administrador',
                ];
                $sendGridService = new SendgridService();
                $sendGridService->sendEmailUser(
                    $templateId,
                    $data['email'],
                    $data['name'],
                    $dataSend
                );
            }
            DB::commit();
            return Response::json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(['status' => 'error', 'messageDev' => $e->getMessage()]);
        }
    }

    public function postIsEmailUnique()
    {
        $validation = Validator::make(Request::all(), ['name' => 'unique:users,email,' . Request::get('id') . ',id']);
        return Response::json($validation->passes() ? true : false);
    }
}
