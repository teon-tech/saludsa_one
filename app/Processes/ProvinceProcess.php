<?php

namespace App\Processes;

use App\Http\Resources\ProvinceResource;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProvinceProcess
{
    /**
     * @var ProvinceRepository
     */
    private $provinceRepository;
    /**
     * 
     * @param ProvinceRepository $provinceRepository
     */
    public function __construct(ProvinceRepository $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

   
    /**
     * @return AnonymousResourceCollection
     */
    public function findAll(Request $request)
    {
        $provinces = $this->provinceRepository->findAll();

        ProvinceResource::withoutWrapping();

        return ProvinceResource::collection($provinces);
    }

}
