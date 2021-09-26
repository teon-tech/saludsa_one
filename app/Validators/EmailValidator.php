<?php

namespace App\Validators;

use App\Rules\ExistsPlanRule;
use Illuminate\Validation\Rule;

/**
 * Class EmailValidator
 * @package App\Validators
 */
class EmailValidator extends BaseValidator
{

    public function sendEmailTermsAndConditions($input)
    {
       
        $rules = [
            'first_name' => [
                'required',
            ],
            'last_name' => [
                'required',
            ],
        ];

        $this->validate($input, $rules);
    }
    public function sendEmailSummaryPurchase($input)
    {
       
        $rules = [
            'saleId' => [
                'required',
            ],
        ];

        $this->validate($input, $rules);
    }
}
