<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class OwnerOrderRule implements Rule
{
    private $uid;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
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
        return Order::query()
            ->join('customer', 'customer.id', '=', 'order.customer_id')
            ->where('order.id', $value)
            ->where('customer.firebase_uid', $this->uid)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No existe una orden con el identificador proporcionado.';
    }
}
