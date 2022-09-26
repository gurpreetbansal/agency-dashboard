<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KwListDetail extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kw_list_details';

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
    protected $fillable = ['user_id','kw_list_id','kw_search_idea_id','status'];	

    public function kw_search_idea_data(){
        return $this->belongsTo('App\KwSearchIdea','id','kw_search_idea_id');
    }

     public function kw_list(){
        return $this->belongsTo('App\KwList');
    }
}
