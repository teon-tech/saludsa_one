<?php


namespace App\Http\Controllers\Api;


use App\Http\Resources\ProductResource;
use App\Processes\FavoriteProductProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FavoriteProductController
{
    /**
     * @var FavoriteProductProcess
     */
    private $favoriteProductProcess;

    /**
     * FavoriteProductController constructor.
     * @param FavoriteProductProcess $favoriteProductProcess
     */
    public function __construct(FavoriteProductProcess $favoriteProductProcess)
    {
        $this->favoriteProductProcess = $favoriteProductProcess;
    }

    /**
     * @param Request $request
     * @return ProductResource
     */
    public function create(Request $request)
    {
        return $this->favoriteProductProcess->create($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request)
    {

        return $this->favoriteProductProcess->delete($request);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        return $this->favoriteProductProcess->findAll($request);
    }

}