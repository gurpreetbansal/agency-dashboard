<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DfsLocation extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dfs_locations';

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
    protected $fillable = ['location','location_code','country_iso_code','location_code_parent','location_type'];

    public static function get_location_name($location_id){
        $data = DfsLocation::where('location_code',$location_id)->first();
        return $data->location;
    }
		
}