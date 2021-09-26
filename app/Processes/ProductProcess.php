<?php

namespace App\Processes;

use App\Http\Resources\BasicProductResource;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductProcess
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductValidator
     */
    private $productValidator;

    private $categoryProcess;

    /**
     * ProductProcess constructor.
     * @param ProductRepository $productRepository
     * @param ProductValidator $productValidator
     * @param CategoryProcess $categoryProcess
     */
    public function __construct(ProductRepository $productRepository, ProductValidator $productValidator, CategoryProcess $categoryProcess)
    {
        $this->productRepository = $productRepository;
        $this->productValidator = $productValidator;
        $this->categoryProcess = $categoryProcess;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function findAllBy(Request $request)
    {
        $input = $request->all();

        $categoryIds = null;
        if (isset($input['categoryId']) && $input['categoryId']) {
            $categoryIds = $this->categoryProcess->getChildrenIds($input['categoryId']);
        }

        $products = $this->productRepository->findAllBy($input, $categoryIds);

        BasicProductResource::withoutWrapping();
        return BasicProductResource::collection($products);
    }

    /**
     * @param $productId
     * @return ProductResource
     */
    public function view($productId)
    {
        $input = [
            'productId' => $productId
        ];

        // Validation
        $this->productValidator->viewValidate($input);

        //Repository
        $product = $this->productRepository->findBy($productId);

        //Resource
        ProductResource::withoutWrapping();
        return new ProductResource($product);
    }

     /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function searchSuggested(Request $request)
    {
        $input = $request->all();

        $products = $this->productRepository->searchSuggested($input);

        BasicProductResource::withoutWrapping();
        return BasicProductResource::collection($products);
    }
}
