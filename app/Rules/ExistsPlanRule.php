<?php

namespace App\Rules;

use App\Models\Plan;
use Illuminate\Contracts\Validation\Rule;

class ExistsPlanRule implements Rule
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
        $plan = Plan::query()
                ->where('id', $value)
                ->where('status', 'ACTIVE')
                ->whereNull('deleted_at')
                ->first() ?? Plan::query()
                ->where('code', $value)
                ->where('status', 'ACTIVE')
                ->whereNull('deleted_at')
                ->first()
                ->first();
        return $plan != null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El plan con el identificador proporcionado no existe.';
    }
}

