<?php

namespace App\Validators;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
/**
 * Class OrderValidator
 * @package App\Validators
 */
class SaleValidator extends BaseValidator
{

    /**
     * @param $input
     */
    public function saveSale($input)
    {

        $rules = [
            "customer" => [
                'required',
                'array',
            ],
            "customer.name" => [
                'string',
            ],
            "customer.email" => [
                'string',
            ],
            "customer.phone" => [
                'required',
            ],
            "customer.father_last_name" => [
                'string',
            ],
            "customer.mother_last_name" => [
                'string',
            ],
            "customer.document_type" => [
                'string',
            ],
            "customer.document" => [
                'string',
            ],
            "customer.years_old" => [
                'integer',
                'required'
            ],
            "customer.birthdate" => [
                'string',
            ],
            "customer.province_id" => [
                'integer',
                'required',
                Rule::exists('province', 'id')
                    ->where('status', Config::get('constants.active_status'))
            ],
            "customer.direction" => [
                'string',
                'required'
            ],
            "customer.civil_status" => [
                'string',
            ],
            "customer.gender" => [
                'string',
                'required'
            ],
            "billing_data" => [
                'required',
                'array',
            ],
            "billing_data.name" => [
                'required',
                'string',
            ],
            "billing_data.last_name" => [
                'required',
                'string',
            ],
            "billing_data.document_type" => [
                'required',
                'string',
            ],
            "billing_data.document" => [
                'required',
                'string',
            ],
            "billing_data.direction" => [
                'required',
                'string',
            ],
            "plans" => [
                'required',
                'array',
            ],
            "plans.*.id" => [
                'required',
                'integer'
            ],
            "plans.*.monthlyPrice" => [
                'required',
            ],
            "plans.*.annualPrice" => [
                'required',
            ],
            "plans.*.hospital_id" => [
                'required',
                'integer',
                Rule::exists('hospital', 'id')
                    ->where('status', Config::get('constants.active_status'))
            ],
            "plans.*.subscription_type" => [
                'required',
                'string',
            ],

        ];
        $this->validate($input, $rules);
    }

    /**
     * @param $orderId
     */

}
