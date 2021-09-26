<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageParameter extends Model
{

    protected $table = 'image_parameter'; //Database table used by the model


    public static $extensions = [
        '.jpg' => 'JPG',
        '.png' => 'PNG',
        '.gif' => 'GIF'
    ];
    public static $entities = [
        'PRODUCT' => 'Producto',
        'CATEGORY' => 'Categoría',
        'PUBLICITY' => 'Publicidad',
        'PROVIDER' => 'Proveedor',
        'COUPON' => 'Cupón',
        'PLAN' => 'Plan',
        'COVERAGE' => 'Cobertura',
    ];

    const TYPE_RACE = 'RACE';
    const TYPE_CHALLENGE = 'CHALLENGE';
    const TYPE_SPONSOR = 'SPONSOR';
    const TYPE_REWARD = 'REWARD';
    const TYPE_PRODUCT = 'PRODUCT';
    const TYPE_PROVIDER = 'PROVIDER';
    const TYPE_CATEGORY = 'CATEGORY';
    const TYPE_PUBLICITY = 'PUBLICITY';
    const TYPE_PLAN = 'PLAN';
    const TYPE_COVERAGE = 'COVERAGE';
}