<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CampaignData;

class BacklinkSummary extends Model {

/**
* The database table used by the model.
*
* @var string
*/
protected $table = 'backlink_summary';     

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
    protected $fillable = [
        'user_id','request_id','referringDomains','referringSubDomains','referringLinks','noFollowLinks','doFollowLinks','referringIps','referringSubnets','domainZoneEdu','domainZoneGov','typeText','typeImg','typeRedirect','mainPageLinks', 'domainRank', 'facebookLinks', 'pinterestLinks',  'linkedinLinks', 'vkLinks', 'status','deleted_at'
    ];


    public static function GetBacklinksCount($request_id){
        $result = BacklinkSummary::where('request_id',$request_id)->orderBy('id','desc')->first();
        if(!empty($result)){
            return $result->referringDomains;
        } else {
            return 0;
        }
    }


    public static function GetBacklinksCountNew($order_type,$user_id){
        $result = BacklinkSummary::
        where('user_id',$user_id)
        ->groupBy('request_id')
        ->orderBy('id', 'desc')
        ->skip(0)
        ->take(10)
        ->get();

        foreach ($result as $key => $value) {
            if(!empty($value->referringDomains)){
                $final[$value->request_id] =  $value->referringDomains;
            }else{
                $final[$value->request_id] =  0;
            }
        }



        if($order_type == 'asc'){
            asort($final);
        }elseif($order_type == 'desc'){
            arsort($final);
        }
        return $final;
    }


    public static function cron_GetBacklinksCount($request_id){
        $result = BacklinkSummary::where('request_id',$request_id)->orderBy('id','desc')->first();
        $referringDomains = 0;
        if(!empty($result)){
            $referringDomains =  $result->referringDomains;
        } 

        $if_exists = CampaignData::where('request_id',$request_id)->first();

        if(!empty($if_exists)){
            CampaignData::where('request_id', $request_id)->update([
                'backlinks_count'=>$referringDomains
            ]);
        }else{
            CampaignData::create([
                'request_id'=>$request_id,
                'backlinks_count'=>$referringDomains
            ]);
        }
    }

}
