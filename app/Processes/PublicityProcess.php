<?php
namespace App\Processes;


use App\Http\Resources\PublicityResource;
use App\Repositories\PublicityRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicityProcess
{
    private $publicityRepository;

    /**
     * PublicityProcess constructor.
     * @param PublicityRepository $publicityRepository
     */
    public function __construct(PublicityRepository $publicityRepository)
    {
        $this->publicityRepository = $publicityRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function findAll()
    {
        $publicity = $this->publicityRepository->findAll();

        PublicityResource::withoutWrapping();
        return PublicityResource::collection($publicity);
    }
}
