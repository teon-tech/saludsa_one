<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'messages_type_plan';

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
        'type_plan_id_1',
        'type_plan_id_2',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_plan_id_1' => 'integer',
        'type_plan_id_2' => 'integer',
        'message' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getMessage($typePlanId1, $typePlanId2){

        $query = $this->query();
        $query->where('type_plan_id_1', '=', $typePlanId1);
        $query->where('type_plan_id_2', '=', $typePlanId2);
        $message = $query->first();
        
        return $message ? $message->message : null;

    }
}