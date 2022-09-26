<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KwSearchIdea extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kw_search_ideas';

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
    protected $fillable = ['kw_search_id','campaign_id','user_id','search_term','category','location_id','language_id','competition','competition_index','sv','page_bid_low','page_bid_high','sv_trend','status'];	

    public function kwListData(){
        return $this->hasMany('App\KwListDetail','kw_search_idea_id','id');
    }

    public function scopeWithWhereHas($query, $relation, $constraint){
     return $query->whereHas($relation, $constraint)
     ->with([$relation => $constraint]);
    }

    public static function delete_associated($kw_search_id){
        $delete_existing = KwSearchIdea::where('kw_search_id',$kw_search_id)->pluck('id');
        KwListDetail::whereIn('kw_search_idea_id',$delete_existing)->delete();
        KwSearchIdea::where('kw_search_id',$kw_search_id)->delete();
    }

}