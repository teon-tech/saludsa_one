<?php


namespace App\Validators;


use App\Rules\OwnerFavoriteProductRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class FavoriteProductValidator extends BaseValidator
{
    /**
     * @param $input
     */
    public function create($input)
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
            'uid' => [
                'required',
                Rule::exists('customer', 'firebase_uid')
            ]
        ];
        $this->validate($input, $rules, $message);
    }

    /**
     * @param $input
     */
    public function delete($input)
    {
        $uid = $input['uid'] ?? null;
        $message = [
            'productId.required' => 'El identificador del producto es requerido',
            'productId.exists' => 'El producto con el identificador proporcionado no existe'
        ];
        $rules = [
            'productId' => [
                'required',
                new OwnerFavoriteProductRule($uid)
            ],
            'uid' => [
                'required',
                Rule::exists('customer', 'firebase_uid')
            ]
        ];
        $this->validate($input, $rules, $message);
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
            ]
        ];
        $this->validate($input, $rules);
    }
}