<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\KeywordSearch;
use App\CampaignData;
use DB;

class CampaignData extends Model {

   // use Sortable;

    protected $table = 'campaign_data';

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
    protected $fillable = ['request_id','backlinks_count','keywords_count','keyword_avg','top_three','top_ten','top_twenty','top_hundred'];

     public static function keywordsData($request_id){
        $count_of_keywords = KeywordSearch::where('request_id',$request_id)->count();
        $results = KeywordSearch::
        select(
            DB::raw('sum(CASE WHEN position > 0 THEN 1 ELSE 0 END) AS hundred'),
            DB::raw('sum(CASE WHEN position <= 20 AND position > 0 THEN 1 ELSE 0 END) AS twenty'),
            DB::raw('sum(CASE WHEN position <= 10 AND position > 0 THEN 1 ELSE 0 END) AS ten'),
            DB::raw('sum(CASE WHEN position <= 3 AND position > 0 THEN 1 ELSE 0 END) AS three')
        )
        ->where('request_id',$request_id)
        ->first();


        if(!empty($results->three)){
            $three = $results->three;
        }else{
            $three = 0;
        }

        if(!empty($results->ten)){
            $ten = $results->ten;
        }else{
            $ten = 0;
        }

        if(!empty($results->twenty)){
            $twenty = $results->twenty;
        }else{
            $twenty = 0;
        }

        if(!empty($results->hundred)){
            $hundred = $results->hundred;
        }else{
            $hundred = 0;
        }

        $if_exists = CampaignData::where('request_id',$request_id)->first();

        if(!empty($if_exists)){
            $campaign_data = CampaignData::where('request_id',$request_id)->update([
                'keywords_count'=>$count_of_keywords,
                'top_three'=>$three,
                'top_ten'=>$ten,
                'top_twenty'=>$twenty,
                'top_hundred'=>$hundred
            ]);
        }else{      
            $campaign_data = CampaignData::create([
                'request_id',$request_id,
                'keywords_count'=>$count_of_keywords,
                'top_three'=>$three,
                'top_ten'=>$ten,
                'top_twenty'=>$twenty,
                'top_hundred'=>$hundred
            ]);
        }
    }



}