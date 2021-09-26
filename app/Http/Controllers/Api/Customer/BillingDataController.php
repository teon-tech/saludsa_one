<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Processes\BillingDataProcess;
use App\Transformers\BillingDataTransformer;
use Illuminate\Http\Request;


class BillingDataController extends Controller
{
    /**
     * @var BillingDataProcess
     */
    private $process;

    /**
     * @var BillingDataTransformer
     */
    private $transformer;

    /**
     * CustomerController constructor.
     * @param BillingDataProcess $process
     * @param BillingDataTransformer $transformer
     */
    public function __construct(BillingDataProcess $process, BillingDataTransformer $transformer)
    {
        $this->process = $process;
        $this->transformer = $transformer;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param $customer_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $customer_id)
    {
        $data = $request->all();
        $model = $this->process->store($data, $customer_id);
        return $this->response->item($model, $this->transformer)->setStatusCode(201);
    }

    /**
     * @param Request $request
     * @param $customer_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(Request $request, $customer_id, $id)
    {
        $data = $request->all();
        $model = $this->process->update($data, $customer_id, $id);
        return $this->response->item($model, $this->transformer);
    }

    /**
     * @param $customer_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($customer_id, $id)
    {
        $model = $this->process->show($customer_id, $id);
        return $this->response->item($model, $this->transformer);
    }

    /**
     * @param Request $request
     * @param $customer_id
     * @return \Dingo\Api\Http\Response
     */
    public function all(Request $request, $customer_id)
    {
        $data = $this->process->findAll($customer_id);
        return $this->response->paginator($data, $this->transformer);
    }

    /**
     * @param $customer_id
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function delete($customer_id, $id)
    {
        $this->process->delete($customer_id, $id);
        return $this->response->noContent();
    }

}
