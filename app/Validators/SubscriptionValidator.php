<?php

namespace App\Validators;

use App\Rules\ExistsSubscriptionSaleRule;

/**
 * Class SubscriptionValidator
 * @package App\Validators
 */
class SubscriptionValidator extends BaseValidator
{

    /**
     * @param $input
     */
    public function create($input)
    {
        $rules = [
            "token" => [
                'required',
            ],
            "saleId" => [
                'required'
            ],
        ];
        $this->validate($input, $rules);
    }

}
