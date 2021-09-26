<?php


namespace App\Http\Controllers\Api;


use App\Http\Resources\ProductResource;
use App\Processes\ProductProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends ApiBaseController
{

    /**
     * @var ProductProcess
     */
    private $productProcess;

    /**
     * ProductController constructor.
     * @param ProductProcess $productProcess
     */
    public function __construct(ProductProcess $productProcess)
    {
        $this->productProcess = $productProcess;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function findAllBy(Request $request)
    {
        return $this->productProcess->findAllBy($request);
    }

    /**
     * @param $productId
     * @return ProductResource
     */
    public function view($productId)
    {
        return $this->productProcess->view($productId);
    }

    /**
     * @param Request $request
     * @return ProductResource
     */
    public function searchSuggested(Request $request)
    {
        return $this->productProcess->searchSuggested($request);
    }
}
