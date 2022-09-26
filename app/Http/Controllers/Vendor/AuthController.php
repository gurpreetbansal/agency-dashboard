<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\UserPackage;
use App\User;
use Crypt;
use Session;
use Illuminate\Support\Facades\Hash;
use App\RegionalDatabse;
use App\SemrushUserAccount;
use App\DashboardType;
use App\SemrushOrganicMetric;
use App\KeywordSearch;
use App\BacklinkSummary;
use App\ProfileInfo;
use App\CampaignTag;
use App\UserMessage;
use App\ApiBalance;
use App\CampaignData;
use App\GlobalSetting;
use URL;
use Mail;
use DB;

use App\Views\ViewCampaign;
use App\Traits\ClientAuth;

use App\ScheduleReport;

class AuthController extends Controller {
	use ClientAuth;

	public function showLogin($domain_name=null) {
		return view('vendor.login',['domain_name'=>$domain_name]);
	}

	public function doLogin(Request $request) {
		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required'
		]);

		if ($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		} else {
			
			$host = \Route::current()->parameter('account');

			$userdata = User::where('email',$request->email)->first();

			if($userdata){
				$check = Hash::check($request->password,$userdata->password);
				
				if($check == 1){
					
					Auth::login($userdata);

					if(Auth::user()->company_name != ''){
						if($host == '' || $host == null){
							$hostname = Auth::user()->company_name;
							return redirect('https://' . $hostname . '.' . \config('app.APP_DOMAIN') . 'dashboard/'.Crypt::encrypt(Auth::user()->id));
						}else{
							if($host == Auth::user()->company_name){
								return redirect('/dashboard');
							}else{
								return redirect('/login')->with('error', 'Oops!, Wrong combination of Email-Address And Password!');
							}
						}
						
					}elseif(Auth::user()->company_name == '' || Auth::user()->company_name == null){
						$hostData = User::where('id',Auth::user()->parent_id)->first();
						return redirect('https://' . $hostData->company_name . '.' . \config('app.APP_DOMAIN') . 'dashboard/'.Crypt::encrypt(Auth::user()->id));
						
					}
				}else{
					return redirect('/login')->with('error', 'Oops!, Wrong combination of Email-Address And Password!');
				}
			}
		}
	}

	public function dashboard($id = null, $uid = null) {
		$dfs_user_data = array();
		if (!empty($uid)) {
			$user = User::where('id', Crypt::decrypt($uid))->first();
			Auth::login($user);
		}
		$message = UserMessage::where('user_id',Auth::user()->id)->first();


		$email_verified = Auth::user()->email_verified;
		$is_admin = Auth::user()->is_admin;
		
		if($is_admin == 1){
			$dfs_user_data = ApiBalance::where('name','DFS')->first();
		}


		if($email_verified == 1){
			$regional_db = RegionalDatabse::where('status', 1)->select('id', 'short_name', 'long_name')->get();
			$user_package = UserPackage::with('package')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
			$dashboardTypes = DashboardType::where('status',1)->get();

			$getCampaigns = SemrushUserAccount::with('MozData')->where('user_id', Auth::user()->id)->where('status', 0)->paginate(10);

			$keywordsCount = KeywordSearch::where('user_id',Auth::user()->id)->count();
			$project_count = SemrushUserAccount::where('user_id',Auth::user()->id)->where('status','!=',2)->count();

			if($keywordsCount > 0){
				$keywordsCount = $keywordsCount;
			}else{
				$keywordsCount = 0;
			}

			if($project_count > 0){
				$project_count = $project_count;
			}else{
				$project_count = 0;
			}


			$managers = ProfileInfo::where('manager_name','!=',NULL)->where('user_id',Auth::user()->id)->groupBy('manager_name')->get();

			$get_managers = User::where('role_id',3)->where('parent_id',Auth::user()->id)->get();


			return view('vendor.dashboard', ['user_package' => $user_package, 'regional_db' => $regional_db, 'getCampaigns' => $getCampaigns, 'project_count' => $project_count,'dashboardTypes'=>$dashboardTypes,'managers'=>$managers,'role'=>Auth::user()->role_id,'get_managers'=>$get_managers,'message'=>$message,'keywordsCount'=>$keywordsCount,'dfs_user_data'=>$dfs_user_data]);
		}
		if($email_verified == 0){
			return view('vendor.email_verify',['user_id'=>Auth::user()->id]);
		}
	}

	public function changepassword() {

		return view('vendor.changepassword');
	}

	public function change_password(Request $request) {
		$validator = Validator::make($request->all(), [
			'current_password' => 'required',
			'password' => 'required|min:6|max:15',
			'confirm_password' => 'same:password',
		], [
			'current_password.required' => 'The field is required',
		]);
		$validator->after(function ($validator)use($request) {
			if (!Hash::check($request->current_password, auth()->user()->password)) {
				$validator->errors()->add('current_password', 'Your current password is incorrect.');
			}
		});
		if ($validator->fails()) {
			return back()->withErrors($validator);
		} else {
			User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
			return back()->with('success', 'Password Updated successfully!');
		}
	}


	public function ajax_active_campaigns(Request $request){
	// echo "<pre>";	
	// print_r($request->all());
	// die;
		$auth_user = Auth::user()->id;
		$getUser = User::findorfail($auth_user);
		
		
		$searchValue = $request['tag_name'];
		$fieldVal = $request['field'];
		$string = @$request['search']["value"];
		$field = ['domain_name','domain_url'];

		$searcherArr = RegionalDatabse::get_search_arr();


		$ids = [];
		if(isset($request['start']) && isset($request['length'])){
			$start = $request['start'];
			$length = $request['length'];
		}else{
			$start = 0;
			$length =10;
		}

		$user_role = $getUser->role_id; 

		if($getUser->parent_id!=''){
			$results = SemrushUserAccount::
			whereIn('id', explode(',',$getUser->restrictions))
			->where('status', 0)
			->where(function ($query) use($string, $field) {
				for ($i = 0; $i < count($field); $i++){
					$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
				}      
			})
			->skip($request['start'])->take($request['length'])
			->get();	

			$result_count = SemrushUserAccount::
			whereIn('id', explode(',',$getUser->restrictions))
			->where('status', 0)
			->where(function ($query) use($string, $field) {
				for ($i = 0; $i < count($field); $i++){
					$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
				}      
			})
			->count();
		}else{

			if (!isset($searchValue) && empty($searchValue)) {
				$results = SemrushUserAccount::
				where('user_id', $auth_user)
				->where('status', 0)
				->where(function ($query) use($string, $field) {
					for ($i = 0; $i < count($field); $i++){
						$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
					}      
				})
				->skip($request['start'])->take($request['length'])
				->get();	

				$result_count = SemrushUserAccount::
				where('user_id', $auth_user)
				->where('status', 0)
				->where(function ($query) use($string, $field) {
					for ($i = 0; $i < count($field); $i++){
						$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
					}      
				})
				->count();		
			}
			elseif(isset($searchValue) && !empty($searchValue) && $fieldVal == 'manager'){
				$data = User::where('name',$searchValue)->where('role_id',3)->where('parent_id',$auth_user)->first();
				$ids = explode(',',$data->restrictions);

				// $data = ProfileInfo::select('request_id')->where('manager_name',$searchValue)->get();

				// foreach($data as $kk=>$val){
				// 	$ids[] = $val->request_id;
				// }
				$results = SemrushUserAccount::
				whereIn('id', $ids)
				->where('status', 0)
				->skip($request['start'])->take($request['length'])
				->get();

				$result_count = SemrushUserAccount::
				whereIn('id', $ids)
				->where('status', 0)
				->count();

			}
			elseif(isset($searchValue) && !empty($searchValue) && $fieldVal == 'tag'){
				foreach($searchValue as $kk=>$vv){
					$camps = CampaignTag::where('tag','LIKE','%'.$vv.'%')->get();
					foreach($camps as $camp){
						$ids[] = $camp->request_id;
					}
				}

				$results = SemrushUserAccount::
				where('user_id', $auth_user)
				->where('status', 0)
				->whereIn('id',$ids)
				->skip($start)->take($length)
				->get();

				$result_count = SemrushUserAccount::
				where('user_id', $auth_user)
				->where('status', 0)
				->whereIn('id',$ids)
				->count();
			}

		}
		$data = array();

		foreach ($results as $key => $result) {
			$manager_name  = $mimage = $tags = $action = '';

			if(isset($result->tags) && !empty($result->tags)){
				$explode_tags = explode(',',$result->tags);
				foreach ($explode_tags as $key => $value) {
					$tags .='<span class="badge badge-primary ml-2">'.$explode_tags[$key].'</span>';
				}				
			}

			$totalKeywords  = SemrushOrganicMetric::DFSKeywords($result->id);
			$backlinksCount = BacklinkSummary::GetBacklinksCount($result->id);
		//	$managerImage = ProfileInfo::getmanagerImage($result->id,$auth_user);
			$managerDetails = User::
			whereRaw("find_in_set($result->id, restrictions)")
			->select('id','name','profile_image')
			->where('role_id',3)
			->first();
			// echo "<pre>";
			// print_r($managerDetails);

			if(isset($managerDetails->name) && !empty($managerDetails->name)){
				$manager_name = $managerDetails->name;
			}

			if(isset($managerDetails->profile_image)){
				$mm = 	URL::asset('/public/storage/'.$managerDetails->profile_image);
				$mimage = 	'<img src="'.$mm.'" alt="image">';
			}

			$key = array_search($result->regional_db, array_column($searcherArr, 'value'));


			$searchlocation = explode('.', $searcherArr[$key]['key']);

			if(count($searchlocation) >2){
				$location = '.'.$searchlocation[1].'.'.$searchlocation[2];
			}else{
				$location = '.'.$searchlocation[1];
			}

			// $link = URL::asset('/public/flags/'.$result->regional_db.'.png');
			$getDbDetails = RegionalDatabse::where('short_name',$result->regional_db)->first();
			$link = URL::asset('/public/storage/database_flags/'.$getDbDetails->flag);
			


			if($user_role == 2){
				$action = '<a data-id="'.$result->id.'" data-name="'.$result->domain_name.'" data-url="'.$result->domain_url.'" class="archive_row btn btn-small" href="javascript:;" data-placement="top" title="" data-hover="tooltip" data-original-title="Archive">  <i class="fa fa-archive"></i> </a><a class="btn btn-small shareModal" href="javascript:;" data-placement="top" title="" data-hover="tooltip" data-id="'.$result->id.'"  data-toggle="modal" data-original-title="Share" data-target="#shareModal"><i class="fa fa-share"></i></a><a class="btn btn-small" href="'.url('/campaign-settings/'.$result->id).'" data-placement="top" title="" data-hover="tooltip" data-id="'.$result->id.'" ><i class="fa fa-cog"></i></a>';
			}

			$data[] = [
				'<a href="https://'. $result->domain_url .'" target="_blank" data-toggle="tooltip" title="" data-original-title="Open in new window">		<i class="fa fa-external-link ml-0 mr-1"></i>    </a> <a href="/new-dashboard/'.$result->id.'" class="ml-0 mr-1">'. $result->domain_name .'</a> <a href="#" data-toggle="tooltip" title="" data-original-title="Google Analytics">  <i class="fa fa fa-area-chart"></i>    </a>		<a href="#" target="_blank" data-toggle="tooltip" title="" data-original-title="Google Search Console">		<i class="fa fa fa-search"></i></a><a data-toggle="tooltip" title="" data-original-title="'.$manager_name.'"><span class="manager_imgae">'.$mimage.'</span></a>'.$tags,

				$result->regional_db != 'us' ? '<figure class="country-icon"><img src="/public/vendor/images/google-logo-icon.png"></figure> '. $location : '<figure class="country-icon"><img src="/public/vendor/images/google-logo-icon.png"></figure> .com',


				'<span class=""><img src="'.$link.'"></span>',

				$totalKeywords['avg'] > 0 ? $totalKeywords['total'].' | <span class="green">'.$totalKeywords['avg'].'%</span>' : ( $totalKeywords['avg'] < 0 ? $totalKeywords['total'].' | <span class="red">'.$totalKeywords['avg'].'%</span>' : $totalKeywords['total'].' | <span class="white">'.$totalKeywords['avg'].'%</span>') ,
				$backlinksCount,
				$action
			];	
		}

		$result_count = SemrushUserAccount::
		where('user_id', $auth_user)
		->where('status', 0)
		->where(function ($query) use($string, $field) {
			for ($i = 0; $i < count($field); $i++){
				$query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
			}      
		})
		->count();


		$json_data = array(
			"draw"            => intval($request['draw']),   
			"recordsTotal"    => $result_count,  
			"recordsFiltered" => $result_count,
			"data"            => $data   
		);

		return response()->json($json_data);

	}


	public function ajax_filter_campaigns(Request $request){
		$auth_user = Auth::user()->id;
		$values = $output  = $res = [];

		if(!empty($request['query']['term']) && isset($request['query']['term']))
		{
			$term= $request['query']['term'];
			$data = CampaignTag::
			select('id','tag')
			->where('user_id', $auth_user)
			->where('tag','LIKE','%'.$term.'%')
			->groupBy('tag')
			->get();

			foreach ($data as $key=> $product) {
				$res[$key]['name'] = $product->tag;
				$res[$key]['id'] = $product->tag;
			}
		// $values = array_filter($res);

		}
		return response()->json($res);
	}

	public function resend_verification_email (Request $request){
		$user_id = $request['user_id'];
		$app_domain = \config('app.APP_DOMAIN');
		$user = User::where('id', $user_id)->first();
		$email_token = base64_encode(now().$user_id);
		$link = 'https://' . $user->company_name . '.' . $app_domain . 'confirmation/'.$email_token;
		$data = array('name' => $user->name, 'email' => $user->email, 'from' => \config('app.MAIL_FROM_NAME'), 'link' => $link);
		// echo "<pre>";
		// print_r($data);
		// die;
		\Mail::send(['html' => 'mails/front/email_verification'], $data, function($message) use($user) {
			$message->to($user->email, $user->name)->subject
			('Verify your email - Agency Dashboard');
			$message->from(\config('app.mail'), 'Agency Dashboard');
		});
		if (\Mail::failures()) {
			$response['status'] = 0;
			$response['message']= 'Error sending email!';
		} else {
			User::where('id',$user_id)->update(['email_verification_token'=>$email_token,'email_sent_at'=>now()]);
			$response['status'] = 1;
			$response['message']= 'Verification email sent successfully!';
		}
		return response()->json($response);
	}


	public function ajax_archive_campaign(Request $request){
		$update = SemrushUserAccount::where('id',$request['request_id'])->update(['status'=>1]);
		ScheduleReport::remove_archived_campaign_report($request['request_id']);
		if($update){
			$response['status']=1;
			$response['message']='Project archieved successfully!';
			SemrushUserAccount::make_project_json();
		}else{
			$response['status']=0;
			$response['message']='Error!! Please try again.';
		}
		return response()->json($response);
	}

	public function archieved_projects(){
		return view('vendor.archieved_projects');
	}


	public function dashboard_new($id = null, $uid = null,$logged_in_as=null){

		$dfs_user_data = $pdfcrowd_data = array();
		$project_count = 0;
		if (!empty($uid)) {
			$user = User::where('id', Crypt::decrypt($uid))->first();
			if(!empty($logged_in_as)){
				$user->setAttribute('super_user', 'admin');
			}
			Auth::login($user);
		}

		$data = GlobalSetting::uploading_changes();
		if($data == true || $data == 1){
			return \View::make('errors.uploading_changes');
		}
		
		$message = UserMessage::where('user_id',Auth::user()->id)->first();

		$is_admin = Auth::user()->is_admin;
		$dfs_user_data = '';
		$pdfcrowd_data = '';
		if($is_admin == 1){
			$dfs_user_data = ApiBalance::where('name','DFS')->first();
			$pdfcrowd_data = ApiBalance::select('id','name','balance')->where('name','pdfcrowd')->first();
		}



		$email_verified = Auth::user()->email_verified;

		$user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child

		if($email_verified == 0){
			return view('vendor.email_verify',['user_id'=>Auth::user()->id]);
		}

		if(\Request::segment(1) !== 'profile-settings'){
			$check = User::check_subscription($user_id); 
			if($check == 'expired'){
				return view('vendor.expired_subscription');
			}
		}  


		$regional_db = array();
		
		return view('vendor.dashboard-new',['message'=>$message,'dfs_user_data'=>$dfs_user_data,'pdfcrowd_data'=>$pdfcrowd_data,'role'=>Auth::user()->role_id,'regional_db' => $regional_db]);
	}

	private function all_data(){
		$final = array();
		$data = SemrushUserAccount::select('domain_url','id')->get();
		
		foreach($data as $key=>$value){
			$favicon = SemrushUserAccount::get_favicon($value->domain_url);
			SemrushUserAccount::where('id',$value->id)->update([
				'favicon'=>$favicon
			]);
		}
		
	}



	/*private  function campaign_list ($limit,$query,$column_name,$order_type,$query_type){
		$field = ['domain_name','domain_url'];
		$auth_user = Auth::user()->id;
		$getUser = User::findorfail($auth_user);
		$searcherArr = RegionalDatabse::get_search_arr();
		$user_role = $getUser->role_id; 


		$result = SemrushUserAccount::where('status', 0)->orderBy('is_favorite','desc');

		if($getUser->parent_id != ''){
			$result = SemrushUserAccount::where('status', 0)->whereIn('id', explode(',',$getUser->restrictions))->orderBy('is_favorite','desc');
			if(!empty($query)){
				
				if($query_type == 'project:'){
					$result->Where(function ($dta) use($query, $field) {
						for ($i = 0; $i < count($field); $i++){
							$dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
						} 
					});
				}

				if($query_type == 'tags:'){
					$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
					$result->whereIn('id',$ids);
				}

				if($query_type == 'manager:'){
					$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$getUser->parent_id)->first();	
					$idss = array();
					if($data != null){				
						$idss = explode(',',$data->restrictions);
					}
					$result->whereIn('id', $idss);
				}

				if($query_type == 'client:'){
					$result->where('clientName', 'LIKE',  '%' . $query .'%');
				}				
			} 
		}else{
			$result = SemrushUserAccount::where('status', 0)->where('user_id', $auth_user)->orderBy('is_favorite','desc');
			if(!empty($query)){
				
				if($query_type == 'project:'){
					$result->Where(function ($dta) use($query, $field) {
						for ($i = 0; $i < count($field); $i++){
							$dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
						} 
					});
				}

				if($query_type == 'tags:'){
					$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
					$result->whereIn('id',$ids);
				}

				if($query_type == 'manager:'){
					$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$auth_user)->first();	
					$idss = array();
					if($data != null){				
						$idss = explode(',',$data->restrictions);
					}
					$result->whereIn('id', $idss);
				}

				if($query_type == 'client:'){
					$result->where('clientName', 'LIKE',  '%' . $query .'%');
				}				
			} 
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
		->paginate($limit);	
		return $results;
	}*/

	private  function campaign_list ($limit,$query,$column_name,$order_type,$query_type){
		$field = ['domain_name','domain_url'];
		$auth_user = Auth::user()->id;
		$getUser = User::findorfail($auth_user);
		$searcherArr = RegionalDatabse::get_search_arr();
		$user_role = $getUser->role_id; 


		/*$result = SemrushUserAccount::where('status', 0)->orderBy('is_favorite','desc');*/

		if($getUser->parent_id != ''){
			$result = ViewCampaign::where('status', 0)->whereIn('id', explode(',',$getUser->restrictions))->orderBy('is_favorite','desc');
			if(!empty($query)){
				
				if($query_type == 'project:'){
					$result->Where(function ($dta) use($query, $field) {
						for ($i = 0; $i < count($field); $i++){
							$dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
						} 
					});
				}

				if($query_type == 'tags:'){
					$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
					$result->whereIn('id',$ids);
				}

				if($query_type == 'manager:'){
					$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$getUser->parent_id)->first();	
					$idss = array();
					if($data != null){				
						$idss = explode(',',$data->restrictions);
					}
					$result->whereIn('id', $idss);
				}

				if($query_type == 'client:'){
					$result->where('clientName', 'LIKE',  '%' . $query .'%');
				}
				
			} 
		}else{
			$result = ViewCampaign::where('status', 0)->where('user_id', $auth_user)->orderBy('is_favorite','desc');
			if(!empty($query)){
				
				if($query_type == 'project:'){
					$result->Where(function ($dta) use($query, $field) {
						for ($i = 0; $i < count($field); $i++){
							$dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
						} 
					});
				}

				if($query_type == 'tags:'){
					$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
					$result->whereIn('id',$ids);
				}

				if($query_type == 'manager:'){
					$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$auth_user)->first();	
					$idss = array();
					if($data != null){				
						$idss = explode(',',$data->restrictions);
					}
					$result->whereIn('id', $idss);
				}

				if($query_type == 'client:'){
					$result->where('clientName', 'LIKE',  '%' . $query .'%');
				}
			} 
		}
		if($column_name == 'domain_name'){
			$result->orderBy($column_name,$order_type);
		}

		if($column_name == 'domain_register'){
			$result->orderBy('domain_register',$order_type);
		}

		if($column_name == 'searcher'){
			$result->orderBy('regional_db',$order_type);
		}

		if($column_name == 'country'){
			$result->orderBy('regional_db',$order_type);
		}

		if($column_name == 'backlinks'){
			$result->orderBy('backlinks_count',$order_type);
		}

		/*if($column_name == 'backlinks'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("backlinks_count")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
			}*/

			if($column_name == 'keywords'){
				$result->orderBy('keywords_count',$order_type);
			}

		/*if($column_name == 'keywords'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("keywords_count")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
			}*/

		/*if($column_name == 'top3'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_three")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
			}*/

			if($column_name == 'top3'){
				$result->orderBy('top_three',$order_type);
			}

			if($column_name == 'top10'){
				$result->orderBy('top_ten',$order_type);
			}

		/*if($column_name == 'top10'){
			if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_ten")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));
			}*/

			if($column_name == 'top20'){

				$result->orderBy('top_twenty',$order_type);

			/*if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_twenty")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));*/
			}

			if($column_name == 'top100'){

				$result->orderBy('top_hundred',$order_type);

			/*if($order_type == 'asc'){
				$order_key = 'orderBy';
			}else{
				$order_key = 'orderByDesc';
			}
			$result->$order_key(CampaignData::select("top_hundred")
				->whereColumn('request_id','semrush_users_account.id')
				->latest()
				->limit(1));*/
			}


			$results = $result
			->paginate($limit);	

			/*dd($results);*/
			return $results;
		}



		private function keywordsData($request_id){
			$results = KeywordSearch::
			select(
				DB::raw('sum(CASE WHEN position > 0 THEN 1 ELSE 0 END) AS hundred'),
				DB::raw('sum(CASE WHEN position <= 20 AND position > 0 THEN 1 ELSE 0 END) AS twenty'),
				DB::raw('sum(CASE WHEN position <= 10 AND position > 0 THEN 1 ELSE 0 END) AS ten'),
				DB::raw('sum(CASE WHEN position <= 3 AND position > 0 THEN 1 ELSE 0 END) AS three'),
				DB::raw('sum(CASE WHEN position > 0 AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_hundred'),
				DB::raw('sum(CASE WHEN (position <= 20 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
				DB::raw('sum(CASE WHEN (position <= 10 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
				DB::raw('sum(CASE WHEN (position <= 3 and position > 0) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')				
			)
			->where('request_id',$request_id)
			->first();


		// 

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

			if(!empty($results->since_hundred)){
				$since_hundred = $results->since_hundred;
			}else{
				$since_hundred = 0;
			}
			if(!empty($results->since_twenty)){
				$since_twenty = $results->since_twenty;
			}else{
				$since_twenty = 0;
			}
			if(!empty($results->since_ten)){
				$since_ten = $results->since_ten;
			}else{
				$since_ten = 0;
			}
			if(!empty($results->since_three)){
				$since_three = $results->since_three;
			}else{
				$since_three = 0;
			}


			$output = array('hundred'=>$hundred,'twenty'=>$twenty,'ten'=>$ten,'three'=>$three,'since_hundred'=>$since_hundred,'since_twenty'=>$since_twenty,'since_ten'=>$since_ten,'since_three'=>$since_three);

			return $output;
		}


		public function ajax_fetch_campaign_data (Request $request){

			if($request->ajax())
			{
				$limit = $request['limit'];
				$query = $request['query'];
				$column_name = $request['column_name'];
				$order_type = $request['order_type'];
				$query_type = $request['query_type'];

				$campaign_data = $this->campaign_list($limit,$query,$column_name,$order_type,$query_type);
			// echo "<pre>";
			// print_r($campaign_data);
			// die;

				return view('vendor.dashboard_campaigns', compact('campaign_data'))->render();
			}
		}

		public function ajax_fetch_campaign_pagination (Request $request){
			if($request->ajax())
			{
				$limit = $request['limit'];
				$query = $request['query'];
				$column_name = $request['column_name'];
				$order_type = $request['order_type'];
				$query_type = $request['query_type'];
				$campaign_data = $this->campaign_list($limit,$query,$column_name,$order_type,$query_type);
				return view('vendor.dashboard_pagination', compact('campaign_data'))->render();
			}
		}


		public function ajax_archive_campaigns(Request $request){
			$update = SemrushUserAccount::whereIn('id',$request['checked'])->update(['status'=>1]);
			ScheduleReport::remove_archived_campaigns_report($request['checked']);

			if($update){
				$response['status'] = 1;
				$response['message'] = 'Campaigns Archived successfully.';
				SemrushUserAccount::make_project_json();
			}else{
				$response['status'] = 0;
				$response['message'] = 'Error!! Please try again';
			}
			return response()->json($response);
		}


		public function ajax_favourite_project(Request $request){
			$result = SemrushUserAccount::
			where('id',$request['request_id'])
			->first();
			if(isset($result) && !empty($result)){
				if($result->is_favorite == 0 || $result->is_favorite == null){
					$fav	=	'1';
					$msg = 'Campaign has been marked Favorite';
				}else{
					$fav	=	'0';
					$msg = 'Campaign has been marked unfavorite';
				}

				$update = SemrushUserAccount::where('id',$result->id)->update([
					'is_favorite'=>$fav
				]);

				if($update){
					$response['status'] = '1'; 
					$response['error'] = '0';
					$response['message'] = $msg;

				}else{
					$response['status'] = '0'; 
					$response['error'] = '0';
					$response['message'] = 'Please try again';
				}
			}else{
				$response['status'] = '0'; 
				$response['error'] = '0';
				$response['message'] = 'Please try again';
			}
			return response()->json($response);




		}



		public function archived_campaigns (Request $request){
			if(\Request::segment(1) !== 'profile-settings'){
				$check = User::check_subscription(Auth::user()->id); 
				if($check == 'expired'){
					return redirect()->to('/dashboard');
				}
			} 
			return view('vendor.archieved_campaigns',['role'=>Auth::user()->role_id]);
		}



		private function archived_campaign_list($limit,$query,$column_name,$order_type,$query_type){

			$field = ['domain_name','domain_url'];
			$auth_user = Auth::user()->id;
			$getUser = User::findorfail($auth_user);
			$user_role = $getUser->role_id; 


			$result = SemrushUserAccount::where('status', 1)->orderBy('domain_name','asc');

			if($getUser->parent_id != ''){
				$result = SemrushUserAccount::where('status', 1)->whereIn('id', explode(',',$getUser->restrictions));
				if(!empty($query)){

					if($query_type == 'project:'){
						$result->Where(function ($dta) use($query, $field) {
							for ($i = 0; $i < count($field); $i++){
								$dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
							} 
						});
					}

					if($query_type == 'tags:'){
						$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
						$result->whereIn('id',$ids);
					}

					if($query_type == 'manager:'){
						$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$getUser->parent_id)->first();	
						$idss = array();
						if($data != null){				
							$idss = explode(',',$data->restrictions);
						}
						$result->whereIn('id', $idss);
					}

					if($query_type == 'client:'){
						$result->where('clientName', 'LIKE',  '%' . $query .'%');
					}
				} 
			}else{
				$result = SemrushUserAccount::where('status', 1)->where('user_id', $auth_user);
				if(!empty($query)){

					if($query_type == 'project:'){
						$result->Where(function ($dta) use($query, $field) {
							for ($i = 0; $i < count($field); $i++){
								$dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
							} 
						});
					}

					if($query_type == 'tags:'){
						$ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
						$result->whereIn('id',$ids);
					}

					if($query_type == 'manager:'){
						$data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$auth_user)->first();	
						$idss = array();
						if($data != null){				
							$idss = explode(',',$data->restrictions);
						}
						$result->whereIn('id', $idss);
					}

					if($query_type == 'client:'){
						$result->where('clientName', 'LIKE',  '%' . $query .'%');
					}
				}
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
			->paginate($limit);

			return $results;
		}

		public function ajax_fetch_archived_campaign_data(Request $request){
			if($request->ajax())
			{
				$limit = $request['limit'];
				$query = $request['query'];
				$column_name = $request['column_name'];
				$order_type = $request['order_type'];
				$query_type = $request['query_type'];

				$campaign_data = $this->archived_campaign_list($limit,$query,$column_name,$order_type,$query_type);

				return view('vendor.dashboard_archived_campaigns', compact('campaign_data'))->render();
			}
		}

		public function ajax_fetch_archived_campaign_pagination(Request $request){
			if($request->ajax())
			{
				$limit = $request['limit'];
				$query = $request['query'];
				$column_name = $request['column_name'];
				$order_type = $request['order_type'];
				$query_type = $request['query_type'];

				$campaign_data = $this->archived_campaign_list($limit,$query,$column_name,$order_type,$query_type);
				return view('vendor.dashboard_archived_pagination', compact('campaign_data'))->render();
			}
		}

		public function ajax_delete_archived_project(Request $request){
			$delete = SemrushUserAccount::where('id',$request['request_id'])->update(['status'=>2,'deleted_at'=>now()]);
			if($delete){
				$response['status'] = 1;
				$response['message'] = 'Campaign deleted successfully';
				SemrushUserAccount::make_project_json();
			}else{
				$response['status'] = 0;
				$response['message'] = 'Error!! deleting campaign';
			}
			return response()->json($response);
		}


		public function ajax_restore_archived_project(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$project_count = SemrushUserAccount::where('user_id',$user_id)->where('status',0)->count();
			$user_package = UserPackage::select('projects')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
			if($project_count < $user_package->projects){
				$restore = SemrushUserAccount::where('id',$request['request_id'])->update(['status'=>0]);
				if($restore){
					$response['status'] = 1;
					$response['message']='Campaign restored successfully!';
					SemrushUserAccount::make_project_json();
				}else{
					$response['status'] = 0;
					$response['message']='Error!! Please try again.';
				}
			}else{
				$response['status'] = 2;
				$response['message']='Please upgrade your package to continue.';
			}
			return response()->json($response);
		}


		public function ajax_delete_campaigns(Request $request){
			$delete = SemrushUserAccount::whereIn('id',$request['checked'])->update(['status'=>2,'deleted_at'=>now()]);
			if($delete){
				$response['status'] = 1;
				$response['message'] = 'Campaign deleted successfully';
				SemrushUserAccount::make_project_json();
			}else{
				$response['status'] = 0;
				$response['message'] = 'Error!! deleting campaigns';
			}
			return response()->json($response);

		}

		public function ajax_restore_campaigns(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$project_count = SemrushUserAccount::where('user_id',$user_id)->where('status',0)->count();
			$user_package = UserPackage::select('projects')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();

			if(count($request['checked']) > $user_package->projects){
				$response['status'] = 2;
				$response['message']= "You've selected more projects than in your package";
			}else{
				if($project_count < $user_package->projects){
					$delete = SemrushUserAccount::whereIn('id',$request['checked'])->update(['status'=>0,'deleted_at'=>null]);
					if($delete){
						$response['status'] = 1;
						$response['message'] = 'Campaigns restored successfully';
						SemrushUserAccount::make_project_json();
					}else{
						$response['status'] = 0;
						$response['message'] = 'Error!! restoring campaigns';
					}
				}else{
					$response['status'] = 2;
					$response['message']='Please upgrade your package to continue.';
				}
			}
			return response()->json($response);

		}


		private function getSiteFavicon($url)
		{
			$ch = curl_init('http://www.google.com/s2/favicons?domain='.$url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
			$data = curl_exec($ch);
			curl_close($ch);

			header("Content-type: image/png; charset=utf-8");
			echo $data;
		}


		/*project tags*/

		public function ajax_get_project_tags(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$tags = CampaignTag::where('request_id',$request['campaign_id'])->where('user_id',$user_id)->get();
			$html = '';
			if(count($tags) > 0){
				$html .= '<div class="form-group m-height">';
				foreach($tags as $key=>$value){
					$html .= '<label><span class="custom-checkbox"></span>'.$value->tag.'<a href="javascript:;" class="delete_project_tag" data-tag-id="'.$value->id.'" data-request-id="'.$value->request_id.'"  data-value="'.$value->tag.'"><span uk-icon="icon: close"></span></a></label>';
				}
				$html .='</div>';
			}

			$response['html']  = $html;
			return response()->json($response);
		}


		public function ajax_save_project_tags(Request $request){
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$result = SemrushUserAccount::select('id','tags')->where('id',$request['campaign_id'])->first();

			if(!empty($request['tags'])){
				$tags = explode(',',$request['tags']);
				if(isset($result->tags) && !empty($result->tags)){
					$count_tags = count(explode(',',$result->tags));
					if(($count_tags + count($tags)) > 3){
						$response['status'] = 'count-error';
						return response()->json($response);
					}

					$tags_value = DB::raw("CONCAT(tags, '," . $request['tags'] . "')");
				}else{
					$tags_value = $request['tags'];
				}


				SemrushUserAccount::where('id',$request['campaign_id'])->update([
					'tags' => $tags_value
				]);

				foreach ($tags as $key => $value) {
					$update = CampaignTag::create([
						'user_id'=>$user_id,
						'request_id'=>$request['campaign_id'],
						'tag'=>trim($value)
					]);
				}
			}

			if($update){
				$response['status'] = 'success';
			}else{
				$response['status'] = 'error';
			}

			return response()->json($response);
		}

		public function ajax_delete_project_tag(Request $request){
			$response = array();
			$delete = CampaignTag::where('id',$request->tag_id)->delete();
			$data = SemrushUserAccount::select('id','tags')->where('id',$request->request_id)->first();
			$final_string = $this->remove_from_string($data->tags,$request->tag);
			$updated = SemrushUserAccount::where('id',$request->request_id)->update([
				'tags' =>$final_string
			]);
			if($updated){
				$response['status'] = 1;
				$response['message'] = 'Tag removed successfully.';
			}else{
				$response['status'] = 0;
				$response['message'] = 'Error, Please try again.';
			}
			return response()->json($response);
		}


		private function remove_from_string($string,$value){
			$hdnListCL= explode(',',$string);

			$index = array_search($value,$hdnListCL);
			if($index !== false){
				unset($hdnListCL[$index]);
			}

			if(count($hdnListCL) == 0){
				$hdnListCL= NULL;
			}else{
				$hdnListCL=implode(',',$hdnListCL);
			}
			return $hdnListCL;
		}

		public function ajax_get_package_subscription(){
			$response = array();
			$keywordsCount = $project_keywords = $project_count = $package_projects = 0;
			$package_name = '';
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$user_package = UserPackage::with('package')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
			if(isset($user_package->package->name)){
				$package_name = $user_package->package->name;
			}
			if(Auth::user()->parent_id <> null){
				$user = Auth::user();
				$keywordsCount = $user->check_active_project_keywords($user->restrictions);
				$project_count = $user->check_active_project_count($user->restrictions);
				$response['keyword_detail'] = $keywordsCount;
				$response['project_detail'] = $project_count;
			}else{		
				$project_count = SemrushUserAccount::where('user_id',$user_id)->where('status',0)->count();
				$keywordsCount = KeywordSearch::check_keyword_count($user_id);

				if($keywordsCount > 0){
					$keywordsCount = $keywordsCount;
				}

				if(isset($user_package->keywords)){
					$project_keywords = $user_package->keywords;
				}

				if(isset($user_package->projects)){
					$package_projects = $user_package->projects;
				}			
				$response['keyword_detail'] = $keywordsCount.'<span>/'.$project_keywords.'</span>';
				$response['project_detail'] = $project_count.'<span>/'.$package_projects.'</span>';
			}


			$response['package_name'] = $package_name;
			return response()->json($response);
		}

		public function ajax_get_keyword_detail(){
			$output  = array();
			$user_id = User::get_parent_user_id(Auth::user()->id);
			$result = KeywordSearch::
			whereHas('SemrushUserData', function($query) use ($user_id){
				$query->where('status', 0)
				->where('user_id',$user_id);
			});
			if(Auth::user()->parent_id != null){
				$user = Auth::user();
				$result->whereIn('request_id',explode(',',$user->restrictions));
			}

			$result->select(
				DB::raw('count(life_ranking) AS total'),
				DB::raw('sum(CASE WHEN life_ranking > 0 THEN 1 ELSE 0 END) AS keywords_up'),
				DB::raw('sum(CASE WHEN position > 0 THEN 1 ELSE 0 END) AS hundred'),
				DB::raw('sum(CASE WHEN position <= 50 AND position > 0 THEN 1 ELSE 0 END) AS fifty'),
				DB::raw('sum(CASE WHEN position <= 30 AND position > 0 THEN 1 ELSE 0 END) AS thirty'),
				DB::raw('sum(CASE WHEN position <= 20 AND position > 0 THEN 1 ELSE 0 END) AS twenty'),
				DB::raw('sum(CASE WHEN position <= 10 AND position > 0 THEN 1 ELSE 0 END) AS ten'),
				DB::raw('sum(CASE WHEN position <= 3 AND position > 0 THEN 1 ELSE 0 END) AS three'),
				DB::raw('sum(CASE WHEN ((position > 30 AND position <= 100)  AND (start_ranking = 0 or start_ranking > 100)) THEN 1 ELSE 0 END) AS since_hundred'),
				DB::raw('sum(CASE WHEN ((position <= 30 and position > 20)  AND (start_ranking > 30 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_thirty'),
				DB::raw('sum(CASE WHEN ((position <= 20 and position > 10)  AND (start_ranking > 20 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_twenty'),
				DB::raw('sum(CASE WHEN ((position <= 10 and position > 3) AND (start_ranking > 10 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_ten'),
				DB::raw('sum(CASE WHEN ((position <= 3 and position > 0) AND (start_ranking >= 4 or start_ranking = 0)) AND life_ranking > 0 THEN 1 ELSE 0 END) AS since_three')
			);

			$results = $result->first();


			$total = ($results->total)?:'0';
			$keywords_up = ($results->keywords_up)?:'0';
			$hundred = ($results->hundred)?:'0';
			$fifty = ($results->fifty)?:'0';
			$thirty = ($results->thirty)?:'0';
			$twenty = ($results->twenty)?:'0';
			$ten = ($results->ten)?:'0';
			$three = ($results->three)?:'0';
			$since_hundred = ($results->since_hundred)?:'0';
			$since_fifty = ($results->since_fifty)?:'0';
			$since_thirty = ($results->since_thirty)?:'0';
			$since_twenty = ($results->since_twenty)?:'0';
			$since_ten = ($results->since_ten)?:'0';
			$since_three = ($results->since_three)?:'0';
			$output = array('total'=>$total,'keywords_up'=>$keywords_up,'hundred'=>$hundred,'fifty'=>$fifty,'thirty'=>$thirty,'twenty'=>$twenty,'ten'=>$ten,'three'=>$three,'since_hundred'=>$since_hundred,'since_fifty'=>$since_fifty,'since_thirty'=>$since_thirty,'since_twenty'=>$since_twenty,'since_ten'=>$since_ten,'since_three'=>$since_three);

			return response()->json($output);
		}
	}