<?php

namespace App\Validators;

use App\Rules\ExistsPlanRule;
use Illuminate\Validation\Rule;

/**
 * Class PlanValidator
 * @package App\Validators
 */
class PlanValidator extends BaseValidator
{

    /**
     * @param array $dataIds
     */
    public function comparativePlan($dataIds)
    {
        $data = [
            'planIds' => $dataIds,
        ];

        $rule = [
            'planIds' => [
                'required',
                'array',
            ],
            "planIds.*" => [
                'integer',
                Rule::exists('plan', 'id')
                    ->where('status', 'ACTIVE')
                    ->where('isComparative', 'YES')
                    ->where('product', 'PLAN')
                    ->where('deleted_at', null)
            ],
        ];
        $this->validate($data, $rule);

    }

    /**
     * @param $id
     */
    public function findById($id)
    {
        $input = [
            'planId' => $id,
        ];
        $message = [
            'planId.exists' => 'El plan con el identificador proporcionado no existe.'
        ];
        $rules = [
            'planId' => [
                'required',
                new ExistsPlanRule()
            ],
        ];

        $this->validate($input, $rules, $message);
    }

    public function findByCode($code)
    {
        $input = [
            'code' => $code,
        ];
        $message = [
            'code.exists' => 'El plan con el identificador proporcionado no existe.'
        ];
        $rules = [
            'code' => [
                'required',
                Rule::exists('plan', 'code')
                    ->where('status', 'ACTIVE')
                    ->where('deleted_at', null)
            ],
        ];

        $this->validate($input, $rules, $message);
    }

    public function getPlanWithPrice($input)
    {
       
        $rules = [
            'hospitalId' => [
                'required',
                'integer'
            ],
            'age' => [
                'required',
                'integer'  
            ],
            'gender' => [
                'required'
            ],
            'product' => [
                'required'
            ]

        ];

        $this->validate($input, $rules);
    }
}
