<?php


namespace App\Validators;


use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class ProductValidator extends BaseValidator
{

    public function viewValidate($input)
    {
        $message = [
            'productId.required' => 'El identificador del producto es requerido',
            'productId.exists' => 'El producto con el identificador proporcionado no existe'
        ];
        $rules = [
            'productId' => [
                'required',
                Rule::exists('product', 'id')
                    ->where('status', Config::get('constants.active_status'))
            ],
        ];
        $this->validate($input, $rules, $message);
    }

}