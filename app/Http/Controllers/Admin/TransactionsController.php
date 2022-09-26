<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Subscription;
use App\Package;
use App\User;
use App\StripeRefund;
use Mail;

class TransactionsController extends Controller {

    public function index(Request $request) {
        return view('admin.transactions.index');
    }


    public function ajaxTransactions(Request $request){
        $results = Subscription::
        with('userDetail')
        ->skip($request['start'])
        ->take($request['length'])
        ->orderBy('id','desc')
        ->get();

        if ($results) {
            $no =1;
            $data = array();
            foreach ($results as $key => $value) {
                $package_purchased = '';

                $field = ['stripe_price_id','stripe_price_yearly_id'];
                $string =$value->stripe_plan;
                $package = Package::
                where(function ($query) use($string, $field) {
                    for ($i = 0; $i < count($field); $i++){
                        $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
                    }      
                })
                ->select('id','name')
                ->first();
              

                if (strtotime($value->trial_ends_at) >= time() + 300 && !empty($package) && isset($package)) {
                    $package_purchased = $package->name . ' ($' . $value->amount . ')';
                } else if (strtotime($value->trial_ends_at) <= time() + 300 && !empty($package) && isset($package)) {
                   $package_purchased = $package->name . ' ($' . $value->amount . ')';
                }

            $data[] = [
                $no++,
                $value->userDetail->name,
                $value->userDetail->email,
                $value->stripe_id,
                $package_purchased,
                '$'.$value->refunded_amount,
                '<a subscription-id="'.$value->id.'" user-id="'.$value->user_id.'" class="refund_subscription btn btn-small" href="javascript:;" data-placement="top" title="" data-hover="tooltip" data-original-title="Refund"><button type="button" class="btn btn-primary"  data-toggle="modal" data-original-title="Refund" data-target="#refundModal"><i class="fa fa-undo"></i> Refund</button></a>'
                ];
            }
        }

        $resultsCount = Subscription::
        with('userDetail')
        ->get();

        $json_data = array(
            "draw"            => intval( $request['draw'] ),   
            "recordsTotal"    => count($resultsCount),  
            "recordsFiltered" => count($resultsCount),
            "data"            => $data   
        );

        return response()->json($json_data);
    }


    public function ajax_refund_data(Request $request){
        $subscription_id = $request['subscription_id'];
        $refund_type = $request['refund_type'];
        $subscription = Subscription::where('id',$subscription_id)->first();
      
        if($subscription){
             if($refund_type == 'partial'){
                $res['status'] = 2;
            }else{
                $res['status'] = 1;
                $res['amount'] = $subscription->amount - $subscription->refunded_amount;
                $res['msg'] = '$'.$subscription->refunded_amount.' has been partially refunded.';
            }            
        }else{
            $res['status'] = 0;
            $res['amount'] = 0;
        }


        return response()->json($res);
       
    }

    public function ajax_refund_payment(Request $request){
        $subscription_id = $request['subscription_id'];
        $refund_type = $request['refundType'];
        $amount = $request['amount'];
        $subscription = Subscription::where('id',$subscription_id)->first();
        $final_amt = $subscription->amount - $subscription->refunded_amount;

        if($amount <= $final_amt){
            try{
                $stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
                $stripe_response = $stripe->refunds->create([
                  'charge' => $subscription->charge_id,
                  'amount' =>$amount*100
                ]);

                if($stripe_response->status == 'succeeded'){
                    Subscription::where('id',$subscription_id)->update([
                        'refunded_amount' =>$subscription->refunded_amount + $amount,
                        'left_amount' => $subscription->amount - ($subscription->refunded_amount + $amount)
                    ]);

                   $stripe_refund =  StripeRefund::create([
                        'subscription_id'=>$subscription_id,
                        'refund_id'=>$stripe_response->id,
                        'amount'=>$stripe_response->amount/100,
                        'charge_id'=>$stripe_response->charge,
                        'refund_created_on' =>date('Y-m-d',$stripe_response->created),
                        'response'=>json_encode($stripe_response)
                    ]);

                   $status = $this->send_refund_email($stripe_refund->id);
                    if($status == 1){
                        $response['status'] = 1;
                        $response['message'] = 'Amount has been refunded to the source,email sent for refund.';
                    }else{
                        $response['status'] = 2;
                        $response['message'] = 'Amount has been refunded to the source, getting error in sending email.';
                    }
                }else{
                    $response['status'] = 0;
                    $response['message'] = 'Error refunding amount';
                }
                
            } catch (\Exception $e) {
                $response['status'] = 0;
                $response['message'] =  $e->getMessage();
            }
        }else{
            $response['status'] = 0;
            $response['message'] = 'Please enter amount less than or equal to actual amount.';
        }

        return response()->json($response);
    }

    public function send_refund_email($stripe_refund_id){
       $stripe_refund_data =  StripeRefund::findOrFail($stripe_refund_id);
       $get_data = StripeRefund::with('subscription_detail')->where('subscription_id',$stripe_refund_data->subscription_id)->orderBy('id','desc')->get();

        $field = ['stripe_price_id','stripe_price_yearly_id'];
        $string = $get_data[0]->subscription_detail->stripe_plan;
        $package = Package::
        where(function ($query) use($string, $field) {
            for ($i = 0; $i < count($field); $i++){
                $query->orwhere($field[$i], 'LIKE',  '%' . $string .'%');
            }      
        })
        
        ->first();
        $user = User::where('id',$get_data[0]->subscription_detail->user_id)->first();

        $adjusted_total = 0;
        foreach($get_data as $get){
             $adjusted_total += $get->amount;  
        }

       

        $data = array('name'=>$user->name,'recent_refund'=>$stripe_refund_data->amount,'refund_date'=>date('M d, Y',strtotime($stripe_refund_data->refund_created_on)),'refunded_to'=>$user->card_brand.'-'.$user->card_last_four,'description'=>$package->name,'amount'=>$get_data[0]->subscription_detail->amount,'refund_detail'=>$get_data,'adjusted_total'=>$adjusted_total);

        Mail::send(['html' => 'mails/admin/refund_notification'], $data, function($message) use($user) {
            $message->to($user->email, $user->name)->subject
            ('Refund from Agency Dashboard');
            $message->from(\config('app.mail'), 'Agency Dashboard');
        });

        if (\Mail::failures()) {
            $failures = array();
            if( count( Mail::failures() ) > 0 ) {
                $failures[] = Mail::failures()[0];
            }
             return $failures;
        }
        else{
            return true;
        }
        
    }
}