<?php

namespace App\Rules;

use App\Models\Subscription;
use Illuminate\Contracts\Validation\Rule;

class ExistsSubscriptionSaleRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $subscription = Subscription::query()
                ->where('sale_id', $value)
                ->first();
        if($subscription != null){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya existe una susbcripción con el identificador de la venta';
    }
}

