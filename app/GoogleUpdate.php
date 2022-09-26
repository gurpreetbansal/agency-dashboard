<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleUpdate extends Model
{


    protected $table = 'google_updates';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['request_id','search_console','analytics','gmb','adwords','sc_type','analytics_type','gmb_type','adwords_type','facebook','facebook_type','ga4','ga4_type'];


    public static function updateTiming($request_id,$moduleType,$updateType,$updateValue){
        GoogleUpdate::updateOrCreate(
            ['request_id' => $request_id],
            [
                $moduleType => now(),
                $updateType => $updateValue
            ]
        );
    }
  
}