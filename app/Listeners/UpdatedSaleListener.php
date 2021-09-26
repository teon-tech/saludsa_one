<?php

namespace App\Listeners;

use App\Events\GenerateSalesContractEvent;
use App\Models\Sale;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Processes\SaleProcess;

class UpdatedSaleListener
{
    private $saleProccess;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SaleProcess $saleProccess)
    {
        $this->saleProccess = $saleProccess;
    }

    /**
     * Handle the event.
     *
     * @param  GenerateSalesContractEvent  $event
     * @return void
     */
    public function handle(GenerateSalesContractEvent $event)
    {
        $sale = $event->sale;
        if ($sale->isDirty('status_payment') && ($sale->status_payment === Sale::STATUS_PAYMENT_APPROVED)) {
            $this->saleProccess->serviceSigmep($sale);
            $this->saleProccess->salesServiceMethod($sale);
        }
    }
}
