<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Moz;
use App\SemrushUserAccount;

class MozController extends Controller {

	public function check_moz_data(){
		$request_data = SemrushUserAccount::
		whereHas('UserInfo', function($q){
			$q->whereDate('subscription_ends_at', '>=', date('Y-m-d'))
			->where('subscription_status', 1);
		})  
		->select('id','user_id','domain_url','moz_date')
		->where('status',0)
		->where(function($qq){
			$qq->whereNull('moz_date')
			->orWhere(function($query){
				$query
				->whereMonth('moz_date','!=',date('m'))
				->orWhereYear('moz_date','!=',date('Y'));
			});
		})
		->whereIn('id',[3042])		
		//->limit(100)
		->get();



		if(!empty($request_data) && isset($request_data)){
			foreach($request_data  as $semrush_data){
				$data = Moz::
				whereMonth('created_at','=',date('m'))
				->whereYear('created_at','=',date('Y'))
				->where('request_id',$semrush_data->id)
				->orderBy('id','desc')
				->first();

		// 		echo "<pre>";
		// print_r($data);
		// die;

				if(empty($data) && $data == null){
					$domain_url = rtrim($semrush_data->domain_url, '/');
					$insertMozData = Moz::getMozData($domain_url);
					
		echo "<pre>";
		print_r($insertMozData);
		die;
					if ($insertMozData) {
						Moz::create([
							'user_id' => $semrush_data->user_id,
							'request_id' => $semrush_data->id,
							'domain_authority' => $insertMozData->DomainAuthority,
							'page_authority' => $insertMozData->PageAuthority,
							'status' => 0
						]);
						SemrushUserAccount::where('id',$semrush_data->id)->update(['moz_date'=>date('Y-m-d')]);
					} 
					sleep(1);
				}   
			}   
		}
	}

	public function update_moz_data(){
		$data = Moz::
			whereMonth('created_at','=',date('m'))
			->whereYear('created_at','=',date('Y'))
			->orderBy('id','desc')
			->get();

			foreach($data as $value){
				SemrushUserAccount::where('id',$value->request_id)->update(['moz_date'=>date('Y-m-d',strtotime($value->created_at))]);
			}
	}
}