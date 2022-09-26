<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\GoogleAnalyticsUsers ;  
use Auth;   
use Session;

class GmbLocation extends Model
{


    protected $table = 'gmb_locations';

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
    protected $fillable = ['user_id','request_id','google_account_id','account_id','location_id','labels','language_code','location_name','primary_phone','store_code','website_url','status'];

    public static function calculate_weeks($start_date){
        $date1 = new DateTime($start_date);

        $date2 = new DateTime(now());

        $difference_in_weeks = $date1->diff($date2)->days / 7;
        if(round($difference_in_weeks) > 0 && round($difference_in_weeks) <= 52){
            return round($difference_in_weeks).' weeks ago';
        }
        else{
            return date("F d' Y",strtotime($start_date));
        }       
    }

   public static function client_auth($checkIfExists){
        $service_token['access_token']   = $checkIfExists->google_access_token;
        $service_token['token_type']   = $checkIfExists->token_type;
        $service_token['expires_in']  = $checkIfExists->expires_in;
        $service_token['id_token']  = $checkIfExists->id_token;
        $service_token['created']  = $checkIfExists->service_created;
        $service_token['refresh_token']  = $checkIfExists->google_refresh_token;

        $client = new \Google_Client(); 
        $client->setAuthConfig(\config('app.FILE_PATH').\config('app.ANALYTICS_CONFIG'));
        $client->setAccessType('offline');
        $client->addScope(["https://www.googleapis.com/auth/business.manage",'email','profile']);
        $client->setAccessToken($service_token);
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true); 

        return $client;
    }

    public static function google_refresh_token($client,$refresh_token,$getAnalytics_id){
        $client->refreshToken($refresh_token);
        $newtoken = $client->getAccessToken();

        GoogleAnalyticsUsers::where('id',$getAnalytics_id)->update([
            'google_access_token'=> $newtoken['access_token'],
            'token_type'=> $newtoken['token_type'],
            'expires_in'=> $newtoken['expires_in'],
            'google_refresh_token'=> $newtoken['refresh_token'],
            'service_created'=> $newtoken['created'],
            'id_token'=> $newtoken['id_token'],
        ]);
        Session::put('token', $client->getAccessToken());
    }

}
