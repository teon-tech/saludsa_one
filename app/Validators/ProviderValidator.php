<?php


namespace App\Validators;


use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class ProviderValidator extends BaseValidator
{

    public function viewValidate($input)
    {
        $message = [
            'providerId.required' => 'El identificador del proveedor es requerido',
            'providerId.exists' => 'El proveedor con el identificador proporcionado no existe'
        ];
        $rules = [
            'providerId' => [
                'required',
                Rule::exists('provider', 'id')
                    ->where('status', Config::get('constants.active_status'))
            ],
        ];
        $this->validate($input, $rules, $message);
    }

    public function viewByCodeValidate($input)
    {
        $message = [
            'code.required' => 'El identificador del proveedor es requerido',
            'code.exists' => 'El proveedor con el identificador proporcionado no existe'
        ];
        $rules = [
            'code' => [
                'required',
                Rule::exists('provider', 'code')
                    ->where('status', Config::get('constants.active_status'))
            ],
        ];
        $this->validate($input, $rules, $message);
    }

}