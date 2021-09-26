<?php

namespace App\Processes;

use App\Http\Resources\CustomerCompleteResource;
use App\Http\Resources\CustomerResource;
use App\Repositories\CustomerRepository;
use App\Services\SendgridService;
use App\Validators\CustomerValidator;
use Illuminate\Support\Facades\Log;

class CustomerProcess
{
    /**
     * @var CustomerRepository
     */
    private $repository;
    /**
     * @var CustomerValidator
     */
    private $validator;

    /**
     * @var SendgridService
     */
    private $sendGridService;

    /**
     * CustomerProcess constructor.
     * @param CustomerRepository $repository
     * @param CustomerValidator $validator
     */
    public function __construct(CustomerRepository $repository, CustomerValidator $validator,
                                SendgridService $sendGridService)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->sendGridService = $sendGridService;
    }

    /**
     * @param $input
     * @return CustomerResource
     */
    public function store($input)
    {
        $this->validator->validateSave($input);
        $customer = $this->repository->store($input);

        CustomerResource::withoutWrapping();
        return new CustomerResource($customer);
    }

    /**
     * @param $uid
     * @return CustomerCompleteResource
     */
    public function showUid($uid)
    {
        $customer = $this->repository->findByUid($uid);
        CustomerResource::withoutWrapping();
        return new CustomerCompleteResource($customer);
    }


    /**
     * @param $input
     * @return CustomerResource
     */
    public function completeRegister($input, $id)
    {
        $this->validator->validateUpdate($input, $id);
        $model = $this->repository->findById($id);

        $customer = $this->repository->update($model, $input);

        CustomerResource::withoutWrapping();
        return new CustomerResource($customer);
    }
}