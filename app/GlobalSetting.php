<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;


class GlobalSetting extends Model {

	protected $table = 'global_settings';

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
    protected $fillable = ['name','status'];


    public static function uploading_changes(){
        $settings = GlobalSetting::where('name','uploading_changes')->where('status',1)->first();
        if(!empty($settings)){
            if(Auth::user()->id !== 9 && Auth::user()->parent_id !== 9){
                return true;
            }
        }else{
            return false;
        }
    }
    

}
