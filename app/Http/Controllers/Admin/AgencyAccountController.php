<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Views\ViewCampaign;

class AgencyAccountController extends Controller {

	public function agency_account_details($agency_id){
		return view('Admin.dashboard.agency_account_details',compact('agency_id'));
	}	


	public function ajax_fetch_agency_account_data(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];
			$agency_id = $request['agency_id'];
			$column_name = ($request['column_name'])?:'created';
			$order_type = ($request['order_type'])?:'desc';
			$data = $this->agency_list_data($limit,$query,$agency_id,$column_name,$order_type);
			return view('Admin.dashboard.agency_account.table', compact('data'))->render();
		}
	}

	public function ajax_fetch_agency_account_pagination(Request $request){
		if($request->ajax())
		{
			$limit = $request['limit'];
			$query = $request['query'];
			$agency_id = $request['agency_id'];
			$column_name = $request['column_name'];
			$order_type = $request['order_type'];
			$data = $this->agency_list_data($limit,$query,$agency_id,$column_name,$order_type);
			return view('Admin.dashboard.agency_account.pagination', compact('data'))->render();
		}
	}

	private function agency_list_data ($limit,$query,$agency_id,$column_name,$order_type){  
		$field = ['domain_name','domain_url','host_url','clientName'];
		$users = ViewCampaign::
		where('user_id',$agency_id)
		->where(function ($q) use($query, $field) {
			for ($i = 0; $i < count($field); $i++){
				$q->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
			}      
		})
		->orderBy($column_name,$order_type)
		->whereIn('status',[0,1])
		->paginate($limit);
		return $users;
	}
}