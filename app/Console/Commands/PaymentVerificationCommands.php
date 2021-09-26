<?php

namespace App\Console\Commands;

use App\Repositories\SubscriptionRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PaymentVerificationCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment_verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $repository = new SubscriptionRepository();
        $currentDay = Carbon::now()->toDateString();
        $subscriptions = $repository->getSubscriptions();
        foreach ($subscriptions as $subscription) {
            $nextPayment = new Carbon($subscription->next_payment_at);
            $nextPayment = $nextPayment->addDay(3)->toDateString();
            if ($nextPayment == $currentDay) {
                $subscription->status_subscription = 'REJECTED';
                $subscription->status = 'INACTIVE';
                $subscription->reason_status = 'PAYMENT NOT FULFILLED';
                $subscription->save();
            }
        }
    }
}
