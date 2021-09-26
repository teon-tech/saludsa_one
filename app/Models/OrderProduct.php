<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OrderProduct
 * @package App\Http\Models
 *
 * @property int id
 * @property int order_id
 * @property int product_id
 * @property float price
 * @property float tax
 * @property float tax_calculated
 * @property float subtotal
 * @property float total
 * @property array unit_selected
 */
class OrderProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'order_by_products';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'tax',
        'tax_calculated',
        'subtotal',
        'total',
        'unit_selected'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'tax' => 'decimal:2',
        'tax_calculated' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'unit_selected' => 'json'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
