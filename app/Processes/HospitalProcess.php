<?php

namespace App\Processes;

use App\Http\Resources\HospitalResource;
use App\Repositories\HospitalRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HospitalProcess
{
    /**
     * @var HospitalRepository
     */
    private $hospitalRepository;
    /**
     * 
     * @param HospitalRepository $hospitalRepository
     */
    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
    }

   
    /**
     * @return AnonymousResourceCollection
     */
    public function findHospitals(Request $request)
    {
        $input = $request->all();
        $hospitals = $this->hospitalRepository->findAll($input);

        HospitalResource::withoutWrapping();

        return HospitalResource::collection($hospitals);
    }

}
