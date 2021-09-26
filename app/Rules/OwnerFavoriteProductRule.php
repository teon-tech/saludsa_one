<?php

namespace App\Rules;

use App\Models\FavoriteProduct;
use Illuminate\Contracts\Validation\Rule;

class OwnerFavoriteProductRule implements Rule
{

    private $uid;

    /**
     * OwnerFavoriteProductRule constructor.
     * @param $uid
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
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
        return FavoriteProduct::query()
            ->join('customer', 'customer.id', '=', 'favorite_products.customer_id')
            ->where('customer.firebase_uid', $this->uid)
            ->where('favorite_products.product_id', $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No existe el producto entre sus favoritos';
    }
}
