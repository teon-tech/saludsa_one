<?php

namespace App\Processes;

use App\Http\Resources\ProviderResource;
use App\Repositories\ProviderRepository;
use App\Validators\ProviderValidator;
use Illuminate\Http\Request;

class ProviderProcess
{
    /**
     * @var ProviderRepository
     */
    private $providerRepository;

    /**
     * @var ProviderValidator
     */
    private $providerValidator;

    /**
     * ProductProcess constructor.
     * @param ProviderRepository $providerRepository
     * @param ProviderValidator $providerValidator
     */
    public function __construct(ProviderRepository $providerRepository, ProviderValidator $providerValidator)
    {
        $this->providerRepository = $providerRepository;
        $this->providerValidator = $providerValidator;
    }

    /**
     * @param $providerId
     * @return ProviderResource
     */
    public function view($providerId)
    {
        $input = [
            'providerId' => $providerId
        ];

        // Validation
        $this->providerValidator->viewValidate($input);

        //Repository
        $provider = $this->providerRepository->findBy($providerId);

        //Resource
        ProviderResource::withoutWrapping();
        return new ProviderResource($provider);
    }

    /**
     * @param $code
     * @return ProviderResource
     */
    public function viewByCode($code)
    {
        $input = [
            'code' => $code
        ];

        // Validation
        $this->providerValidator->viewByCodeValidate($input);

        //Repository
        $provider = $this->providerRepository->findBy($code,'code');

        //Resource
        ProviderResource::withoutWrapping();
        return new ProviderResource($provider);
    }

     /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getAll(Request $request)
    {
        $input = $request->all();
        $providers = $this->providerRepository->getAll($input);

        ProviderResource::withoutWrapping();
        return ProviderResource::collection($providers);
    }

}
