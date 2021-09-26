<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Processes\CustomerProcess;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /**
     * @var CustomerProcess
     */
    private $process;

    public function __construct(CustomerProcess $process)
    {
        $this->process = $process;
    }

    /**
     * @param Request $request
     * @return CustomerResource
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return $this->process->store($data);
    }


    /**
     * @param Request $request
     * @return CustomerResource
     */
    public function showWithUid($uid)
    {
        return $this->process->showUid($uid);
//        return $this->process->store($data);
    }


    /**
     * @param Request $request
     * @return CustomerResource
     */
    public function completeRegister(Request $request, $id)
    {
        $data = $request->all();
        return $this->process->completeRegister($data,$id);
    }

}
