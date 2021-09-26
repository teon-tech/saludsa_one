<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class CoverageValidator
 * @package App\Validators
 */
class CoverageValidator extends BaseValidator
{

    /**
     * @param Request $request
     * @return bool
     */
    function validate(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'latitude' => 'required',
                'longitude' => 'required'
            ]
        );
        if ($validator->fails()) {
            $this->responseFail($validator->errors()->toArray());
        }
        return true;
    }
}