<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordLocationList extends Model{
	
	 /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keyword_location_list';

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
    protected $fillable = ['loc_id','loc_id_parent','loc_name','loc_name_canonical','loc_type','loc_country_iso_code'];
	
	
	
	public static function flagsByCode($country){
		$data = KeywordLocationList::select('loc_id','loc_country_iso_code')->where('loc_name_canonical',$country)->first();
		return $data;
	}

    public static function getLatLong($address){
        $address = str_replace(" ", "+", $address);
        $url = "https://maps.google.com/maps/api/geocode/json?address=".$address."&key=AIzaSyAyfg5RfXsDreSKWq7-P-VjfW7d2-abe8c";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        
        $lat = $response_a->results[0]->geometry->location->lat;
        $long = $response_a->results[0]->geometry->location->lng;
        $response = $lat.','.$long;
        return $response;
    }
}