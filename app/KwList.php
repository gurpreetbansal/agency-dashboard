<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KwList extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kw_lists';

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
    protected $fillable = ['user_id','campaign_id','name','status'];	

     public function kw_list_detail() {
        return $this->hasMany('App\KwListDetail', 'kw_list_id', 'id');
    }

    public static function get_list_name($list_id){
        $data = KwList::select('name')->where('id',$list_id)->first();
        return $data->name;
    }

}
