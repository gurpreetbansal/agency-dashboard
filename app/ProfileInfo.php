<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use App\GoogleAccountViewData;
use Auth;
use App\SemrushUserAccount;
use Session;
use URL;


class ProfileInfo extends Model {

protected $table = 'profile_info';

protected $appends = array('country_code_val');

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
protected $fillable = ['user_id', 'request_id', 'company_name', 'client_name', 'agency_logo','email', 'contact_no','manager_email','manager_name','country_code','white_label_branding'];

public static function getmanagerImage($request_id,$user_id){
    $path  = 'public/storage/agency_managers/'.$user_id.'/'.$request_id.'/';

    if(file_exists($path)){
        $files1 = array_values(array_diff(scandir($path), array('..', '.')));

        if(!empty($files1)) {
            $image_url = URL::asset('public/storage/agency_managers/'.$user_id.'/'.$request_id.'/'.$files1[0]);
            $response['return_path']    =   $image_url; 
            $response['file_name']      =   $files1[0];
        }else{
            $response   =   '';
        }
        return $response;           
    }
}


public static function agency_logo($request_id,$user_id,$image_name){

    if (file_exists(\config('app.FILE_PATH').'public/storage/agency_logo/'.$user_id.'/'.$request_id)) {
        $path  = 'public/storage/agency_logo/'.$user_id.'/'.$request_id.'/';
        if(file_exists($path)){
            $image_url = URL::asset('public/storage/agency_logo/'.$user_id.'/'.$request_id.'/'.$image_name);

        }
    }else{
        $image_url   =   '';
    }

    return $image_url;   
}

public function getCountryCodeValAttribute(){
    $id = $this->country_code;
    if($id <> null){
        $country_data = Country::where('id',$id)->first();
        return  $country_data->country_code;
    }else{
        return '';
    }
}

}
