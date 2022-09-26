<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DomainAuthority;
use UrlMetrics;
use App\SemrushUserAccount;


class Moz extends Model {

	protected $table = 'mozes';
	
	protected $primaryKey = 'id';
	
	protected $fillable = ['user_id', 'request_id', 'page_authority', 'domain_authority', 'status', 'created_at','updated_at'];

    // const UPDATED_AT = null;
	
	
	public static function getMozData($domain_url) {
		try{
			$moz_data = DomainAuthority::urlMetrics($domain_url, UrlMetrics::DomainAuthority | UrlMetrics::PageAuthority);
			return $moz_data;
		}catch(\Exception $e){
			
		}		
	}

	public static function insertData($user_id,$project_id,$domain_url){
		$url_info = parse_url($domain_url);
		if (!empty($url_info) && isset($url_info['host'])) {
			$url = str_replace("www.", "", $url_info['host']);
		} elseif (!empty($url_info) && isset($url_info['path'])) {
			$url = str_replace("www.", "", $url_info['path']);
		}

		$url = rtrim($url,'/');
		$moz_data = DomainAuthority::urlMetrics($url, UrlMetrics::DomainAuthority | UrlMetrics::PageAuthority);
		if ($moz_data) {
			Moz::create([
				'user_id' => $user_id,
				'request_id' => $project_id,
				'domain_authority' => $moz_data->DomainAuthority,
				'page_authority' => $moz_data->PageAuthority,
				'status' => 0
			]);

			SemrushUserAccount::where('id',$project_id)->update(['moz_date'=>date('Y-m-d')]);
		}
	}

}
