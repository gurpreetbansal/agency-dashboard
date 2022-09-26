<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Package;
use App\SemrushUserAccount;
use App\KeywordSearch;
use App\UserPackage;
use App\UserEmail;
use App\UserMessage;
use App\Announcement;
use App\GlobalSetting;
use Mail;
use Crypt;
use Auth;
use Artisan;

class SuperUserController extends Controller {

    public function index (){
        $announcements = Announcement::get();
        $globalSettings = GlobalSetting::where('name','site_maintenance')->first();
        return view('admin_bkp.super_user',['announcements'=>$announcements,'globalSettings'=>$globalSettings]);
    }

    public function ajax_client_details(Request $request){
        $string = $request['search']["value"];
        $field = ['name'];
        $users = User::
        with('UserPackage')
        ->where('role_id','=',2)
        // ->where('status','=',1)
        ->where(function ($query) use($string, $field) {
            for ($i = 0; $i < count($field); $i++){
                $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
            }      
        })
        ->skip($request->start)
        ->take($request->length)
        ->get();

        if(!empty($users) && isset($users)){
            $actions = $package_name =  '';
            $i=1;
            $data = array();   
            
            foreach($users as $key=>$value){

                $keyId=0;
                $userPackage = UserPackage::where('user_id',$value->id)->first();

                $user_projects =  SemrushUserAccount::withCount('Keywords')->where('user_id',$value->id)->where('status','!=',2)->get();
                foreach($user_projects as $project){
                    $keyId += $project->keywords_count;

                }

                if ($value->UserPackage) {
                    $package = Package::where('id', $value->UserPackage->package_id)->select('name', 'amount')->first();
                    if(isset($package) && !empty($package)){
                        $package_name = $package->name.' ('.count($user_projects).'/'.$value->UserPackage->projects.')';
                    }

                }        
                $url = 'https://'.$value->company_name.'.'.env('APP_DOMAIN');
                if($value->status ==1){
                    $datavalue = '<a href="javascript:;" data-id="'.$value->id.'" title="Deactivate User" class="suspend_user" data-value="1"><i class="fas fa-pause"></i></a>';
                    $loginAsClientBtn = '<button type="button" class="btn btn-primary loginAsClient" data-id="'.$value->id.'">Login As Client</button>';
                }else{
                    $datavalue ='<a href="javascript:;" data-id="'.$value->id.'" title="Activate User" class="suspend_user" data-value="2"><i class="fas fa-play"></i></a>';
                     $loginAsClientBtn = '<button disabled type="button" class="btn btn-primary loginAsClient" data-id="'.$value->id.'">Login As Client</button>';
                }
                
                $data[] = [
                    '<span class="">'.$i++.'</span>',
                    '<span class="">'.$value->name.'</span>',
                    '<span class="">'.$value->company_name.'</span>',
                    '<span class="">'.$package_name.'</span>',
                    '<span class="">'.$keyId.'/'.@$userPackage->keywords.'</span>',
                    '<span class=""><a href="'.$url.'" title="Access Url"><i class="fas fa-link"></i></a>&nbsp;'.$datavalue.'</span>&nbsp;<a href="javascript:;" data-id="'.$value->id.'" class="EmailUser" data-target="#emailUser" data-toggle="modal" ><i class="fa fa-envelope"></i></a>&nbsp;<a href="javascript:;" data-id="'.$value->id.'" class="messageUser" data-target="#msgUser" data-toggle="modal"><i class="fa fa-comments"></i></a>',
                    $loginAsClientBtn
                ];

            }          

        }

        $json_data = array(
            "draw"            => intval($request['draw']),   
            "recordsTotal"    => count($users),  
            "recordsFiltered" => count($users),
            "data"            => $data   
        );

        return response()->json($json_data);
        
    }

    public function ajax_suspend_user(Request $request){
        //dd($request->all());
        if($request['value']==1){
            $update = User::where('id',$request['user_id'])->update(['status'=>2]);
            $msg = 'User account deactivated.';
        }else if($request['value'] ==2){
            $update = User::where('id',$request['user_id'])->update(['status'=>1]);
            $msg = 'User account activated';
        }
        if($update){
            $res['status']=1;
            $res['message']=$msg;
        }else{
            $res['status']=0;
            $res['message']='Error!! please try again.';
        }
        return response()->json($res);
    }

    public function get_user_email(Request $request){
        $get = User::where('id',$request['user_id'])->first();
        if($get){
            $response['status'] = 1;
            $response['email'] = $get->email;
        }
        else{
            $response['status'] = 0;
        }
        return response()->json($response);
    }

    public function send_email_message(Request $request){
          $response = array();
          $sent = UserEmail::create([
            'user_id'=>$request['user_id'],
            'email_to'=>$request['email'],
            'subject'=>$request['subject'],
            'message'=>$request['msg'],
            'status'=>1
          ]);
      if($sent){
        $subject = $sent->subject;
        $user = User::where('id',$request['user_id'])->first();
        $data = array('msg'=>$sent->message,'subject'=>$subject);
        \Mail::send(['html' => 'mails/admin_send_message'], $data, function($message) use($user,$subject) {
            $message->to($user->email, $user->name)->subject
            ($subject);
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });

        if (\Mail::failures()) {
            UserEmail::where('id',$sent->id)->update(['status'=>2]);
            $response['status'] = 2;
            $response['message'] = 'Error sending email';

        } else {
            $response['status'] = 1;
            $response['message'] = 'Email sent to User.';
        }

    }else{
        $response['status'] = 0;
        $response['message'] = 'Error !!';
    }
    return response()->json($response);
}

public function send_message(Request $request){
    $check = UserMessage::where('user_id',$request['user_id'])->first();
    if(!empty($check)){
       $done =    UserMessage::where('id',$check->id)->update([
        'message'=>$request['msg'],
        'banner'=>$request['banner']
     ]);
   }else{
        $done = UserMessage::create([
            'user_id'=>$request['user_id'],
            'message'=>$request['msg'],
            'banner'=>$request['banner']
        ]);
    }
    if($done){
        $response['status'] = 1;
        $response['message'] = 'Message sent successfully!';
    }else{
        $response['status'] = 0;
        $response['message'] = 'Error!! sending message';
    }
    return response()->json($response);
}

    public function get_user_message(Request $request){
        $get = UserMessage::where('user_id',$request['user_id'])->first();
        if($get){
            $response['status'] = 1;
            $response['msg'] = $get->message;
            $response['banner'] = $get->banner;
        }elseif($get == null){
            $response['status'] = 2;
        }else{
            $response['status'] = 0;
        }
        return response()->json($response);
    }

    public function login_as_client(Request $request){
      //  dd($request->all());
        if($request['user_id'] != ''){
            $user = User::findOrfail($request['user_id']);
           if($user){
              $request->session()->forget('admin_session');
              $request->session()->flush();
              User::where('id',$user->id)->update(['login_as'=>1]);
              $redirect_link = 'https://' . $user->company_name . '.' . \config('app.APP_DOMAIN') . 'dashboard/' . Crypt::encrypt($request['user_id']);

             $response['link'] = $redirect_link;
             $response['prev'] = 'admin';
             $response['status'] = 1;
         }else{
             $response['status'] = 0;
         }
          
             return response()->json($response);
        }

    }

    public function ajax_save_announcement(Request $request){
      $create =   Announcement::create([
            'announcement'=>$request['announcement_text'],
            'announcement_type'=>$request['announcement_type'],
            'status'=>1
        ]);
      if($create){
        $res['status'] = 1;
        $res['msg'] = 'Announcement created successfully!';
      }else{
        $res['status'] = 0;
        $res['msg'] = 'Error !! creating announcement.';
      }
      return response()->json($res);
    }


    public function ajax_delete_announcement(Request $request){
        $deleted = Announcement::where('id',$request['id'])->delete();
        if($deleted){
            $res['status'] =1;
            $res['msg'] = 'Announcement deleted successfully!';
        }else{
            $res['status'] =0;
            $res['msg'] = 'Error!! deleting announcement!';
        }
        return response()->json($res);
    }

    public function ajax_save_global_settings(Request $request){
        
        if($request->has('example')){
            $value = 1;
            Artisan::call('down');
        }else{
            $value = 0;
            Artisan::call('up');
            @unlink(storage_path().'/framework/down');
        }

        $update = GlobalSetting::where('name','site_maintenance')->update([
            'status'=>$value
        ]);
        if($update){
            $res['status'] = 1;
            $res['message'] = 'Settings updated successfully!';
        }else{
            $res['status'] = 0;
            $res['message'] = 'Error updating settings.';
        }
        return response()->json($res);

    }

}