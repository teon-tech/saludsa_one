<?php

namespace App\Processes;


use App\Http\Resources\OrderResource;
use App\Http\Resources\ProviderResource;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Validators\OrderValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class OrderProcess
 * @package App\Processes
 */
class OrderProcess
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderValidator
     */
    private $orderValidator;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * OrderProcess constructor.
     * @param OrderRepository $orderRepository
     * @param OrderValidator $orderValidator
     * @param CustomerRepository $customerRepository
     */
    public function __construct(OrderRepository $orderRepository, OrderValidator $orderValidator, CustomerRepository $customerRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderValidator = $orderValidator;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param Request $request
     * @return OrderResource
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $input = $request->all();

        // Validation
        $this->orderValidator->create($input);

        //Repository
        $customer = $this->customerRepository->findByAtt($input['uid'], 'firebase_uid');

        $dataOrder = [
            'customer_id' => $customer->id,
            'provider_id' => $input['providerId'],
        ];
        $order = $this->orderRepository->create($dataOrder, $input['products']);

        //Resource
        OrderResource::withoutWrapping();
        return new OrderResource($order);
    }

    /**
     * @param $orderId
     * @return OrderResource
     */
    public function view($orderId)
    {
        // Validation
        $this->orderValidator->view($orderId);

        // Repository
        $order = $this->orderRepository->findBy($orderId, 'id');

        //Resource
        OrderResource::withoutWrapping();
        return new OrderResource($order);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        $input = $request->all();

        // Validation
        $this->orderValidator->findAll($input);

        // Repository
        $orders = $this->orderRepository->findAll($input);

        // Resource
        OrderResource::withoutWrapping();
        return OrderResource::collection($orders);
    }

    /**
     * @param Request $request
     * @return OrderResource
     */
    public function qualification(Request $request)
    {
        $input = $request->all();

        // Validation
        $this->orderValidator->qualification($input);

        // Repository
        $order = $this->orderRepository->qualification($input['orderId'], $input['qualification']);

        //Resource
        OrderResource::withoutWrapping();
        return new OrderResource($order);
    }

}