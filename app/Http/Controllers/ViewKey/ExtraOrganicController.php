<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SemrushOrganicSearchData;

class ExtraOrganicController extends Controller
{

	public function extra_organic_keywords($keyenc = null){
		$encription = base64_decode($keyenc);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];
		$keywords = $this->keyword_data(100,$campaign_id,'position','asc','');
		return view('viewkey.seo_sections.organic_keyword_growth.detail',compact('campaign_id','keyenc'));
	}

	private function keyword_data($limit,$campaign_id,$sortType,$sortBy,$query){
		$keywords = SemrushOrganicSearchData::
		where('request_id', $campaign_id)
		->where('keywords','LIKE','%'.$query.'%')
		->orderBy($sortType,$sortBy)
		->paginate($limit);
		
		return $keywords;
	}

	public function ajax_fetch_keyword_data(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$campaign_id = $request['campaignID'];
			$sortType = $request['column_name'];
			$sortBy = $request['reverse_order'];
			$query  = $request['query'];

			$keywords = $this->keyword_data($limit,$campaign_id,$sortType,$sortBy,$query);
			return view('viewkey.seo_sections.organic_keyword_growth.detail_data', compact('keywords','campaign_id'))->render();
		}
	}


	public function ajax_fetch_organic_keyword_data(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$campaign_id = $request['campaignID'];
			$sortType = $request['column_name'];
			$sortBy = $request['reverse_order'];
			$query  = $request['query'];

			$keywords = $this->keyword_data($limit,$campaign_id,$sortType,$sortBy,$query);
			return view('viewkey.seo_sections.organic_keyword_growth.detail_data', compact('keywords','campaign_id'))->render();
		}
	}

	public function ajax_fetch_keyword_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$campaign_id = $request['campaignID'];
			$sortType = $request['column_name'];
			$sortBy = $request['reverse_order'];
			$query  = $request['query'];

			$keywords = $this->keyword_data($limit,$campaign_id,$sortType,$sortBy,$query);
			return view('viewkey.seo_sections.organic_keyword_growth.detail_pagination', compact('keywords','campaign_id'))->render();
		}
	}
}