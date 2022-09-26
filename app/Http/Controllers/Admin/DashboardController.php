<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Crypt;

class DashboardController extends Controller {

    public function ajax_fetch_account_data(Request $request){
        if($request->ajax())
        {
            $limit = $request['limit'];
            $query = $request['query'];

            $data = $this->account_list($limit,$query);

            return view('Admin.dashboard.table', compact('data'))->render();
        }
    }

    public function ajax_fetch_account_pagination(Request $request){
        if($request->ajax())
        {
            $limit = $request['limit'];
            $query = $request['query'];

            $data = $this->account_list($limit,$query);

            return view('Admin.dashboard.pagination', compact('data'))->render();
        }
    }


    private  function account_list ($limit,$query){  
        $field = ['name','email'];
        $users = User::where('role_id',2)
      //  ->with('UserPackage')
        ->where(function ($q) use($query, $field) {
            for ($i = 0; $i < count($field); $i++){
                $q->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
            }      
        })
        ->orderBy('subscription_status','desc')
        ->orderBy('created_at','desc')
        ->paginate($limit); 
        return $users;
    }

    public function login_as_client(Request $request){
        if($request['user_id'] != ''){
            $user = User::findOrfail($request['user_id']);
            if($user){
                $request->session()->forget('admin_session');
                $request->session()->flush();
                User::where('id',$user->id)->update(['login_as'=>1]);
                $redirect_link = 'https://' . $user->company_name . '.' . \config('app.APP_DOMAIN') . 'dashboard/' . Crypt::encrypt($request['user_id']).'/super_user';
                $response['link'] = $redirect_link;
                $response['prev'] = 'admin';
                $response['status'] = 1;
            }else{
                $response['status'] = 0;
            }
            return response()->json($response);
        }

    }


}