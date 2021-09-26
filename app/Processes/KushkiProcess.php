<?php

namespace App\Processes;

use App\Services\KushkiService;
use App\Repositories\SubscriptionRepository;

class KushkiProcess
{

    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @var KushkiService
     */
    private $kushkiService;

    public function __construct(KushkiService $kushkiService , SubscriptionRepository $subscriptionRepository ) {

        $this->kushkiService = $kushkiService;
        $this->subscriptionRepository = $subscriptionRepository; 
    }
}
