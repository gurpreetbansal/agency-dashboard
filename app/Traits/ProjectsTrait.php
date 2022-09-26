<?php

namespace App\Traits;

trait ProjectsTrait {

	public function projectCampaignList($limit = 10,$query,$column_name,$order_type){

		$field = ['domain_name','domain_url'];
		$auth_user = Auth::user()->id;
		$getUser = User::findorfail($auth_user);
		$searcherArr = RegionalDatabse::get_search_arr();
		$user_role = $getUser->role_id; 


		$result = SemrushUserAccount::where('status', 0);

		if($getUser->parent_id != ''){
			$result->whereIn('id', explode(',',$getUser->restrictions));
		}else{
			
			if(!empty($query)){
					//manager
				$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$auth_user)->first();
				$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
				
				if($data != null){
					$ids = explode(',',$data->restrictions);
					$result->whereIn('id', $ids);
				}
						//tags
				if($ids !=null){
					$result->whereIn('id',$ids);
				}
					//domain name/url 
				$result->orwhere(function ($dta) use($query, $field) {
					for ($i = 0; $i < count($field); $i++){
						$dta->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
					}   
				});
			}

			$result->where('user_id', $auth_user);
		}
		if($column_name == 'domain_name'){
			$result->orderBy($column_name,$order_type);
		}

		if($column_name == 'searcher'){
			$result->orderBy('regional_db',$order_type);
		}

		if($column_name == 'country'){
			$result->orderBy('regional_db',$order_type);
		}

		if($column_name == 'backlinks'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("backlinks_count")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($column_name == 'keywords'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("keywords_count")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($column_name == 'top3'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_three")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($column_name == 'top10'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_ten")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($column_name == 'top20'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_twenty")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}

		if($column_name == 'top100'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_hundred")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
		}
		

		$results = $result
		// ->where('user_id',$auth_user)
		->paginate($limit);

		

		return $results;

	}

	public function fail($message='',$code =200,$status =false){
			return response()->json([
	            'code' => $code,
	            'success' => $status,
	            'message' => $message,
	        ]); 
	}
}