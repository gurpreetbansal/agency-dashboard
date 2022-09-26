<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use App\UserPackage;
use App\User;
use App\SemrushUserAccount;
use App\KeywordSearch;
use Auth;
use Image;

class User extends Authenticatable implements JWTSubject
{

    use Notifiable;
    use Billable;


    // protected $appends = array('availability');
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    public function getProfileImageAttribute($value)
    {
        return $value !== null ? url('public/storage/').$value : null;  
    
    }
    public function getPhoneAttribute($value)
    {
        return $value !== null ? $value : '';  
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_name','company', 'phone', 'profile_image','status','email_verified','email_verification_token','email_verified_at','email_sent_at','parent_id','restrictions','role','role_id','login_as','last_login','is_admin','stripe_id','card_brand','card_last_four','subscription_status','subscription_ends_at','initial_background','notification_check_time','card_exp_month','card_exp_year','referer','purchase_mode','user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UserPackage() {
        return $this->hasOne('App\UserPackage', 'user_id', 'id');
        // return $this->hasOne('App\UserPackage', 'user_id', 'id')->where('package_purchase', 1);
    }

    public static function uploadOne($uploadedFile, $folder = null, $disk = 'public', $filename = null) {
		
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);
        return $file;
    }

    public function UserAddress() {
        return $this->hasOne('App\UserAddress', 'user_id', 'id');
    }
    
    public function Subscription(){
        return $this->belongsTo('App\Subscription','id','user_id');
    }

    public static function check_email_exists($email) {
        $check = User::where('email', $email)->first();
        if (!empty($check)) {
            echo "taken";
        } else {
            echo 'not_taken';
        }
        exit();
    }


    public static function get_parent_user_id($user_id){
        $user = User::findorfail($user_id);
        if($user->parent_id != ''){
            return $user->parent_id;
        }else{
            return $user_id; 
        }
       
    }

    public static function get_parent_vanity($user){
        if($user->parent_id !== '' && $user->parent_id !== null){
            $users = User::findorfail($user->parent_id);
            return $users->company_name;
        }else{
            return $user->company_name; 
        }
    }

    public static function get_user_role($user_id){
        $user = User::findorfail($user_id);
        return $user->role_id;       
    }

    public static function get_user_package($user_id){
        $data = UserPackage::where('user_id',$user_id)->first();
        return $data;
    }

    public static function get_manager_details($request_id){
        $managerDetails = User::
            whereRaw("find_in_set($request_id, restrictions)")
            ->select('id','name','profile_image','initial_background')
            ->where('role_id',3)
            ->first();
            return $managerDetails;
    }

    public static function check_subdomain($domain_name){
        $response = User::where('company_name',$domain_name)->first();
        if($response == null){
            return abort(404);
        }      
    }

    public static function get_restricted_projects($restrictions){
        $data = SemrushUserAccount::select('id','domain_name')->whereIn('id',explode(',',$restrictions))->get();
        return $data;
    }

    /*may 10*/
    public static function get_user_role_name($role_id){
        if($role_id == 2){
            return 'Agency Owner';
        }
        if($role_id == 3){
            return 'Manager';
        }
        if($role_id == 4){
            return 'Client - View Only';
        }
    }

    public function check_active_project_count($restrictions){
        $check_status = 0;
        $explode = explode(',',$restrictions);
        $check_status = SemrushUserAccount::where('status',0)->whereIn('id',$explode)->count();
        if(isset($check_status) && !empty($check_status)){
            return $check_status;
        }
    } 

    public function check_active_project_keywords($restrictions){
        $user_id = User::get_parent_user_id(Auth::user()->id);
        $check_status = 0;
        $explode = explode(',',$restrictions);
        $check_status =   KeywordSearch::
        where('user_id',$user_id)
        ->whereIn('request_id',$explode)
        ->whereHas('SemrushUserData', function($query) use ($user_id){
            $query->where('status', 0)
            ->where('user_id',$user_id);
        })
        ->count();
       
        if(isset($check_status) && !empty($check_status)){
            return $check_status;
        }
    }

    public static function get_account_user_image($value){
        $managerDetails = User::where('id',$value)->first();
        $figure = '';
        if(isset($managerDetails)){
            if(isset($managerDetails->profile_image)){
                $image = '<img src="'.$managerDetails->profile_image.'">';
                $figure = '<figure class="agency-owner">'.$image.'</figure>';
            }else{
                $words = explode(' ', $managerDetails->name);
                $initial =  strtoupper(substr($words[0], 0, 1));
                $figure = '<figure class="agency-owner"><figcaption>'.$initial.'</figcaption></figure>'; 
            }

        }
        return $figure;
    }

    public function get_keywords_data($user_id){
        $response = array();
        $keywordsCount = KeywordSearch::check_keyword_count($user_id); 
        $user_package = UserPackage::with('package')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        $response['used_keywords'] = ($keywordsCount)?$keywordsCount:0;
        $response['package_keywords'] = ($user_package->keywords)?$user_package->keywords:0;
        return $response;
    }

    public function get_project_data($user_id){
        $response = array();
        $project_count = SemrushUserAccount::where('user_id',$user_id)->where('status',0)->count();
        $user_package = UserPackage::with('package')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        $response['used_projects'] = ($project_count)?$project_count:0;
        $response['package_projects'] = ($user_package->projects)?$user_package->projects:0;
        return $response;
    }

    public static function resizeImage($image, $folderName,$name){
        $name = '/'.$folderName.'/'.\Str::slug($name) . '_' . time(). '.' . $image->getClientOriginalExtension();
        $fileName =  'app/public'. $name;
        Image::make($image)->fit(164)->save(storage_path($fileName));
        return $name;
    }

    public static function get_random_color(){
        $color_array = array('red','yellow','blue','orange','green');
        $random_keys = array_rand($color_array,1);
        $color = 'background_'.$color_array[$random_keys];
        return $color;
    }

     public static function check_subscription($user_id){
        $response = User::where('id',$user_id)->first();
        if($response <> null && ($response->subscription_status == 0 && $response->subscription_ends_at <=  date('Y-m-d H:i:s')) && ($user_id !== 9)){
            return 'expired';
        }elseif($response <> null && ($response->subscription_status == 0 && $response->subscription_ends_at >=  date('Y-m-d H:i:s'))){
            return 'cancelled';
        }
    }

    public static function get_agency_owner_email($user_id){
        $data = User::select('email')->where('id',$user_id)->first();
        if($data <> null){
            return $data->email;
        }else{
            return '';
        }
    }


    /*admin*/
    public function get_user_active_package($user_id){
        $user_package = UserPackage::with('package')->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
        if($user_package->subscription_type == 'free'){
            return 'Free Forever';
        }
        return ($user_package->price)?'$'.$user_package->price:'-';
    }

    public static function get_client_details($request_id){
        $managerDetails = User::
        whereRaw("find_in_set($request_id, restrictions)")
        ->select('id','name','profile_image','initial_background')
        ->where('role_id',4)
        ->first();
        return $managerDetails;
    }
}