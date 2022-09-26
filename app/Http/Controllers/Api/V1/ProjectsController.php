<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException; 
use Tymon\JWTAuth\JWTAuth; 

use Tymon\JWTAuth\Exceptions\JWTException; 
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException; 

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth;
use App\Traits\ApiStatus;
use App\User;
use App\DashboardType;
use App\RegionalDatabse;
use App\UserPackage;
use App\SemrushUserAccount;
use App\Moz;
use App\SeoAnalyticsEditSection;
use App\CampaignDashboard;
use App\KeywordSearch;
use App\CampaignTag;

class ProjectsController extends Controller
{

	use ApiStatus;
  
	public function getDashboardType(Request $request)
    {

    	$message = "Dashboard type list.";
        $data = DashboardType::select('id','name','status')->where('status',1)->get();
        return $this->success($message,$data);
    }

    public function getDashboardRegion(Request $request)
    {

    	$message = "Regional Database type list.";
        $data = RegionalDatabse::select('id','short_name','long_name','status')->where('status',1)->get();
        return $this->success($message,$data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function getDashboard(Request $request)
    {
        $email_verified = Auth::user()->email_verified;
      
        if($email_verified == 0){
            $message = 'You have reached your project limit, Upgrade to add more projects.';
            return $this->fail($message);
        }

       

        $user_package = UserPackage::with('package')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
        if(isset($user_package->keywords)){
            $project_keywords = $user_package->keywords;
        }else{
            $project_keywords = 0;
        }

        if(isset($user_package->projects)){
            $package_projects = $user_package->projects;
        }else{
            $package_projects = 0;
        }

        if(isset($user_package->package->name)){
            $package_name = $user_package->package->name;
        }else{
            $package_name = '';
        }

        $keywordsCount = KeywordSearch::where('user_id',Auth::user()->id)->count();
        if($keywordsCount > 0){
            $keywordsCount = $keywordsCount;
        }else{
            $keywordsCount = 0;
        }

        $project_count = SemrushUserAccount::where('user_id',Auth::user()->id)->where('status','=',0)->count();
        if($project_count > 0){
            $project_count = $project_count;
        }else{
            $project_count = 0;
        }

        $query = $request->search;
        $column_name = 'is_favorite';
        $order_type = 'desc';
        
        $campaign_data = $this->projectCampaignList(500,$query,$column_name,$order_type);

        // dd($campaign_data);
        $data = ['package_name'=>$package_name,'package_projects'=>$package_projects,'project_keywords'=>$project_keywords,'keywordsCount'=>$keywordsCount,'project_count'=>$project_count
            ,'campaign_data'=>$campaign_data
            
        ];
        $message = "Active Projects List";
        return $this->success($message,$data);
        
    }


    public function getArchivedDashboard(Request $request)
    {

        $email_verified = Auth::user()->email_verified;
      
        if($email_verified == 0){
            $message = 'You have reached your project limit, Upgrade to add more projects.';
            return $this->fail($message);
        }

       

        $user_package = UserPackage::with('package')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
        if(isset($user_package->keywords)){
            $project_keywords = $user_package->keywords;
        }else{
            $project_keywords = 0;
        }

        if(isset($user_package->projects)){
            $package_projects = $user_package->projects;
        }else{
            $package_projects = 0;
        }

        if(isset($user_package->package->name)){
            $package_name = $user_package->package->name;
        }else{
            $package_name = '';
        }

        $keywordsCount = KeywordSearch::where('user_id',Auth::user()->id)->count();
        if($keywordsCount > 0){
            $keywordsCount = $keywordsCount;
        }else{
            $keywordsCount = 0;
        }

        $project_count = SemrushUserAccount::where('user_id',Auth::user()->id)->where('status','!=',2)->count();
        if($project_count > 0){
            $project_count = $project_count;
        }else{
            $project_count = 0;
        }

        $query = $request->search;
        $column_name = 'is_favorite';
        $order_type = 'desc';
        
        $campaign_data = $this->projectArchivedCampaignList(500,$query,$column_name,$order_type);

        // dd($campaign_data);
        $data = ['package_name'=>$package_name,'package_projects'=>$package_projects,'project_keywords'=>$project_keywords,'keywordsCount'=>$keywordsCount,'project_count'=>$project_count
            ,'campaign_data'=>$campaign_data
            
        ];
        $message = "Active Projects List";
        return $this->success($message,$data);
    } 


	/**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function store(Request $request)
    {
        
        $requestData = $request->all();
        $dashboardType_array = $request->dashboardType;
        $user_id = Auth::user()->id;
        
		$user_package = UserPackage::with('package')->where('user_id', $user_id)->select('projects')->orderBy('created_at', 'desc')->first();
		$getCampaignsCount = SemrushUserAccount::where('user_id', $user_id)->where('status', 0)->count();
		if ($user_package->projects <= $getCampaignsCount) {
			
			$message = 'You have reached your project limit, Upgrade to add more projects.';
			return $this->fail($message);
		}

        $user_data = User::where('id', $user_id)->first();
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $url_info = parse_url($request->domain_url);

        if (!empty($url_info) && isset($url_info['host'])) {
            $check_domain = SemrushUserAccount::checkdomainUrl($url_info['host'], $user_id);
            $domain_url = str_replace("www.", "", $url_info['host']);
        } elseif (!empty($url_info) && isset($url_info['path'])) {
            $check_domain = SemrushUserAccount::checkdomainUrl($url_info['path'], $user_id);
            $domain_url = str_replace("www.", "", $url_info['path']);
        }
        $dashboardType = explode(',',$request->dashboardType);
        // dd($dashboardType);

        if ($check_domain == 0) {
            $favicon =  SemrushUserAccount::get_favicon($request->domain_url);
            $semrush_user_account = SemrushUserAccount::create([
                'user_id' => $user_id,
                'domain_name' => $request->domain_name,
                'domain_url' => $request->domain_url,
                'regional_db' => $request->regional_db,
                'clientName' => $user_data->name,
                'token' => $token,
                'dashboard_type'=>$dashboardType_array,
                'favicon'=>$favicon,
                'created' => now(),
                'modified' => now()
            ]);
            if ($semrush_user_account) {
                $last_inserted_id = $semrush_user_account->id;
                $domain_url = rtrim($domain_url, '/');
                if (!empty($last_inserted_id)) {
                    $insertMozData = Moz::getMozData($domain_url);
                    if ($insertMozData) {
                        Moz::create([
                            'user_id' => $user_id,
                            'request_id' => $last_inserted_id,
                            'domain_authority' => $insertMozData->DomainAuthority,
                            'page_authority' => $insertMozData->PageAuthority,
                            'status' => 0,
                            'created_at' => now()
                        ]);
                    }
                    $this->CustomNote($user_id, $last_inserted_id);
                    $this->addCampaignDashboards($user_id, $last_inserted_id,$dashboardType);
                    
                    $message = 'Domain added successfully.';
                     return $this->success($message);

                } else {
                    $message = 'Getting error, Try again. ';
                    return $this->fail($message);
                }
            } else {
                $message = 'Getting error, Try again.';
                return $this->fail($message);
            }
        } else {
            $message = 'Domain Name already exists';
            return $this->fail($message);
        }

    }

    public function markFavorite(Request $request, $id = null)
    {
        $result = SemrushUserAccount::
        where('id',$id)
        ->first();

        if(isset($result) && !empty($result)){
            if($result->is_favorite == 0 || $result->is_favorite == null){
                $fav    =   '1';
                $message = 'Campaign has been marked Favorite';
            }else{
                $fav    =   '0';
                $message = 'Campaign has been marked unfavorite';
            }

            $update = SemrushUserAccount::where('id',$result->id)->update([
                'is_favorite'=>$fav
            ]);

            if($update){
                return $this->success($message,null);
            }else{
                $message = 'Something went wrong! Please try again.';
                return $this->fail($message);
            }
        }else{
            $message = 'Project does not exist any more.';
            return $this->fail($message);
        }
    }

    public function archiveCampaigns(Request $request, $id = null)
    {
        $result = SemrushUserAccount::where('id',$id)->update([
            'status'=>1
        ]);

        if($result){
            $message = 'Campaigns Archived successfully.';
            return $this->success($message);
        }else{
            $message = 'Error!! Please try again';
            return $this->fail($message);
        }
        
    }

    public function deleteCampaigns(Request $request, $id = null)
    {
        $delete = SemrushUserAccount::where('id',$id)->update(['status'=>2,'deleted_at'=>now()]);
        if($delete){
            $message = 'Project deleted successfully';
            return $this->success($message,null);
        }else{
            $message = 'Error!! deleting project';
            return $this->fail($message);
        }
    }

     public function restoreCampaigns(Request $request, $id = null)
    {
        $delete = SemrushUserAccount::where('id',$id)->update(['status'=>0]);
        if($delete){
            $message = 'Project restored successfully';
            return $this->success($message,null);
        }else{
            $message = 'Error!! Restoring project';
            return $this->fail($message);
        }
    }

    public function projectCampaignList($limit = 500,$query,$column_name,$order_type){

        $field = ['domain_name','domain_url'];
        $auth_user = Auth::user()->id;
        $getUser = User::findorfail($auth_user);
        $searcherArr = RegionalDatabse::get_search_arr();
        $user_role = $getUser->role_id; 


        $result = SemrushUserAccount::with('keywordDataCount')->where('status', 0)->select('id','favicon','domain_name','domain_url','clientName','domain_register','is_favorite');


        if($getUser->parent_id != ''){
            $result->whereIn('id', explode(',',$getUser->restrictions));
        }else{
            
            $result = SemrushUserAccount::with('keywordDataCount')->where('status', 0)->select('id','favicon','domain_name','domain_url','clientName','domain_register','is_favorite')->where('user_id', $auth_user);
            if(!empty($query)){
                //manager
                $data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$auth_user)->first();
                //tags
                $ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
                    
                    //domain name/url 
                $result->Where(function ($dta) use($query, $field,$ids,$data,$result) {
                    for ($i = 0; $i < count($field); $i++){
                        $dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
                    }   

                    if($ids !=null){
                        $dta->orwhere(function($dta) use($ids,$result){
                            $dta->whereIn('id',$ids);
                        });
                    }
                    

                    if($data != null){
                        $dta->orwhere(function($dta) use($data,$result){
                            $idss = explode(',',$data->restrictions);
                            $dta->whereIn('id', $idss);
                        });
                    }

                });

            }

            $result->where('user_id', $auth_user);
        }
        $result->orderBy($column_name,$order_type);
        $results = $result
        // ->where('user_id',$auth_user)
        ->paginate($limit);


        return $results;

    }

    public function projectArchivedCampaignList($limit = 500,$query,$column_name,$order_type){

        $field = ['domain_name','domain_url'];
        $auth_user = Auth::user()->id;
        $getUser = User::findorfail($auth_user);
        $searcherArr = RegionalDatabse::get_search_arr();
        $user_role = $getUser->role_id; 


        // $result = SemrushUserAccount::where('status', 1)->select('id','favicon','domain_name','domain_url','clientName','domain_register','is_favorite');

        $result = SemrushUserAccount::with('keywordDataCount')->where('status', 1)->select('id','favicon','domain_name','domain_url','clientName','domain_register','is_favorite');

        if($getUser->parent_id != ''){
            $result->whereIn('id', explode(',',$getUser->restrictions));
        }else{
             $result = SemrushUserAccount::with('keywordDataCount')->where('status', 1)->select('id','favicon','domain_name','domain_url','clientName','domain_register','is_favorite')->where('user_id', $auth_user);
            
            if(!empty($query) && $query!=null){
                    //manager
                $data = User::where('name','LIKE','%'.$query.'%')->where('role_id',3)->where('parent_id',$auth_user)->first();
                $ids = CampaignTag::where('tag','LIKE','%'.$query.'%')->pluck('request_id')->all();
                
                $result->Where(function ($dta) use($query, $field,$ids,$data,$result) {
                    for ($i = 0; $i < count($field); $i++){
                        $dta->orWhere($field[$i], 'LIKE',  '%' . $query .'%');
                    }   

                    if($ids !=null){
                        $dta->orwhere(function($dta) use($ids,$result){
                            $dta->whereIn('id',$ids);
                        });
                    }
                    

                    if($data != null){
                        $dta->orwhere(function($dta) use($data,$result){
                            $idss = explode(',',$data->restrictions);
                            $dta->whereIn('id', $idss);
                        });
                    }

                });
            }

            $result->where('user_id', $auth_user);
        }
        $result->orderBy($column_name,$order_type);
        $results = $result
        // ->where('user_id',$auth_user)
        ->paginate($limit);

        return $results;

    }


    public function addCampaignDashboards($user_id, $last_inserted_id,$dashboardType_array){
        foreach($dashboardType_array as $id){
            CampaignDashboard::create([
                'user_id'=>$user_id,
                'request_id'=>$last_inserted_id,
                'dashboard_id'=>$id,
                'dashboard_status'=>1
            ]);
        }
    }

    public function CustomNote($user_id, $last_inserted_id) {
        SeoAnalyticsEditSection::create([
            'user_id' => $user_id,
            'request_id' => $last_inserted_id,
            'edit_section' => "<b>Welcome to Your Dashboard!</b>
            <p>This dashboard gives you an at-a-glance view of the aspects of your campaign that are most important to you. And since it's customizable, you can ask your account manager to update it for you.</p>
            <h5>This dashboard shows you: </h5>
            <ul>
            <li>a. Traffic from Google analytics. </li>
            <li>b. Visibility of your campaign in Google from Search Console. </li>
            <li>c. Additional Keywords that you are ranking for from SEMRUSH.</li> 
            <li>d. Work performed by our team in Activity Seaction and much more.</li></ul>
            <p>You can download a PDF copy of whole report by clicking a button in top right section.</p>
            <p>To give us feedback on this tool, please leave a message with your account manager. </p>",
            'edit_area' => 0,
            'created' => now()
        ]);
        return true;
    }



}