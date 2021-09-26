<?php

namespace App\Validators;


use App\Rules\EnableToQualificationOrderRule;
use App\Rules\OwnerOrderRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

/**
 * Class OrderValidator
 * @package App\Validators
 */
class OrderValidator extends BaseValidator
{

    /**
     * @param $input
     */
    function create($input)
    {
        $providerId = $input['providerId'] ?? null;
        $rules = [
            'uid' => [
                'required',
                Rule::exists('customer', 'firebase_uid')
            ],
            'providerId' => [
                'required',
                Rule::exists('provider', 'id')
                    ->where('status', Config::get('constants.active_status')),
            ],
            'products' => [
                'required',
                'array'
            ],
            "products.*.productId" => [
                'required',
                'integer',
                Rule::exists('product', 'id')
                    ->where('status', Config::get('constants.active_status'))
                    ->where('provider_id', $providerId),
            ],
            "products.*.quantity" => [
                'required',
                'integer'
            ],

            "products.*.unit" => [
                'nullable',
                'array'
            ],
            "products.*.unit.unitId" => [
                'nullable'
            ],
            "products.*.unit.name" => [
                'nullable'
            ],
            "products.*.unit.value" => [
                'nullable'
            ]
        ];

        $this->validate($input, $rules);
    }

    /**
     * @param $orderId
     */
    public function view($orderId)
    {
        $input = [
            'orderId' => $orderId
        ];

        $rules = [
            'orderId' => [
                'required',
                Rule::exists('order', 'id')
            ]
        ];

        $this->validate($input, $rules);
    }

    /**
     * @param $input
     */
    public function findAll($input)
    {
        $rules = [
            'uid' => [
                'required',
                Rule::exists('customer', 'firebase_uid')
            ],
        ];
        $this->validate($input, $rules);
    }

    /**
     * @param $input
     */
    public function qualification($input)
    {
        $uid = $input['uid'] ?? null;
        $rules = [
            'uid' => [
                'required',
                Rule::exists('customer', 'firebase_uid')
            ],
            'qualification' => 'required|integer|min:0|max:5',
            'orderId' => [
                'required',
                Rule::exists('order', 'id'),
                new OwnerOrderRule($uid),
                new EnableToQualificationOrderRule()
            ]
        ];
        $this->validate($input, $rules);
    }
}