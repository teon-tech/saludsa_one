<?php


namespace App\Processes;


use App\Http\Resources\ProductResource;
use App\Repositories\CustomerRepository;
use App\Repositories\FavoriteProductRepository;
use App\Validators\FavoriteProductValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;

class FavoriteProductProcess
{
    /**
     * @var FavoriteProductRepository
     */
    private $favoriteProductRepository;

    /**
     * @var FavoriteProductValidator
     */
    private $favoriteProductValidator;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * FavoriteProductProcess constructor.
     * @param FavoriteProductRepository $favoriteProductRepository
     * @param FavoriteProductValidator $favoriteProductValidator
     * @param CustomerRepository $customerRepository
     */
    public function __construct(FavoriteProductRepository $favoriteProductRepository, FavoriteProductValidator $favoriteProductValidator, CustomerRepository $customerRepository)
    {
        $this->favoriteProductRepository = $favoriteProductRepository;
        $this->favoriteProductValidator = $favoriteProductValidator;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param Request $request
     * @return ProductResource
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $this->favoriteProductValidator->create($input);

        $customer = $this->customerRepository->findByAtt($input['uid'], 'firebase_uid');

        $product = $this->favoriteProductRepository->create($customer->id, $input['productId']);

        ProductResource::withoutWrapping();
        return new ProductResource($product);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        $this->favoriteProductValidator->delete($input);

        $customer = $this->customerRepository->findByAtt($input['uid'], 'firebase_uid');

        $this->favoriteProductRepository->delete($customer->id, $input['productId']);

        return Response::noContent();
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        $input = $request->all();
        $this->favoriteProductValidator->findAll($input);

        $products = $this->favoriteProductRepository->findAllBy($input['uid'], $input);

        ProductResource::withoutWrapping();

        return ProductResource::collection($products);
    }
}
