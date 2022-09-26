<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DfsLanguage extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dfs_languages';

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
    protected $fillable = ['language','language_code','language_id','status'];

    public static function get_language($language_id){
        $data = DfsLanguage::where('language_code',$language_id)->first();
        return ($data)?$data->language:'Any Language';
    }

}