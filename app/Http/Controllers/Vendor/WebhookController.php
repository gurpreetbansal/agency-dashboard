<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Stripe;
use App\Traits\StripePayment;


class WebhookController extends Controller {

	use StripePayment;

	public function stripe_webhooks(){
		$stripe = new \Stripe\StripeClient(\config('app.STRIPE_SECRET'));
		$stripe->webhookEndpoints->create([
			'url' => url('/').'/stripe_postback_webhooks',
			'enabled_events' => [
		  		//'charge.captured',
				'customer.subscription.created',
				'customer.subscription.updated',
				'invoice.paid',
				'invoice.payment_succeeded',
				'invoice.updated',
				'invoice.finalized',
				'invoice.payment_failed',
				'invoice.created',
				'invoice.upcoming'  
			],
		]);
	}

	public function getStripeResponse()
	{
		$body = @file_get_contents('php://input');
		$event_json = json_decode($body);
		return $event_json;
	}

	public function stripe_postback_webhooks(Request $request){
		$response = $this->getStripeResponse();
		$stripePayment = $this;


		if(!empty($response))
		{
			switch($response->type) {
				case "invoice.upcoming":
				$param = array();
				if(isset($response->data->object->id)){
					$invoice_id = $response->data->object->id;
				}else{
					$invoice_id = null;
				}

				$param["invoice_id"] = $invoice_id;
				$param["subscription_id"] = $response->data->object->subscription;
				$param["invoice_number"] = $response->data->object->number;
				$param["customer_id"] = $response->data->object->customer;
				$param["billing_email"] = $response->data->object->customer_email;
				$param["currency"] = $response->data->object->currency;
				$param["invoice_status"] = $response->data->object->status;
				$param["invoice_created_date"] = date("Y-m-d H:i:s", $response->data->object->created);
				$param['response'] = $response;

				$i = 0;
				foreach($response->data->object->lines->data as $data)
				{
					$param["invoice_items"][$i]["amount"] = $data->amount;
					$param["invoice_items"][$i]["currency"] = $data->currency;
					$param["invoice_items"][$i]["quantity"] = $data->quantity;
					$param["invoice_items"][$i]["description"] = $data->description;
					$i++;
				}

				$stripePayment->insertInvoice($param);
				break;
				case "customer.subscription.created":
				$param = array();
				if($response->data->object->discount != null){
					$amount = $response->data->object->items->data[0]->price->unit_amount/100;
					$discount = ($amount * $response->data->object->discount->coupon->percent_off)/100;
					$coupon_name = $response->data->object->discount->coupon->name;
				}else{
					$discount = 0.00;
					$coupon_name = '';
				}

				if($response->data->object->status =='trialing'){
					$trial_end_date = date("Y-m-d H:i:s", $response->data->object->current_period_end);
				}else{
					$trial_end_date = '';
				}
				$param["subscription_id"] = $response->data->object->id;
				$param["customer_id"] = $response->data->object->customer;
				$param["subscription_plan"] = $response->data->object->items->data[0]->price->id;
				$param["subscription_interval"] = $response->data->object->items->data[0]->price->recurring->interval_count . " " .$response->data->object->items->data[0]->price->recurring->interval;
				$param["subscription_status"] = $response->data->object->status;
				$param["current_period_start"] = date("Y-m-d H:i:s", $response->data->object->current_period_start);
				$param["current_period_end"] = date("Y-m-d H:i:s", $response->data->object->current_period_end);
				$param["subscription_created_date"] = date("Y-m-d H:i:s", $response->data->object->created);
				$param['amount'] = $response->data->object->items->data[0]->price->unit_amount;
				$param['quantity'] = $response->data->object->items->data[0]->quantity;
				$param['coupon_name'] = $coupon_name;
				$param['trial_end_date'] = $trial_end_date;
				$param['discount'] = $discount;
				$param['response'] = $response;
				$stripePayment->insertSubscription($param);
				break;
				case "customer.subscription.updated":
				$param = array();
				$param["subscription_id"] = $response->data->object->id;
				$param["subscription_status"] = $response->data->object->status;
				$param["current_period_start"] = date("Y-m-d H:i:s",$response->data->object->current_period_start);
				$param["current_period_end"] = date("Y-m-d H:i:s",$response->data->object->current_period_end);
				$param['response'] = $response;
				$stripePayment->updateSubscription($param);
				break;

				case "invoice.payment_succeeded":
				$param["invoice_id"] = $response->data->object->id;
				$param["invoice_status"] = $response->data->object->status;
				$param["subscription_id"] = $response->data->object->subscription;
				$param["customer_id"] = $response->data->object->customer;
				$param['response'] = $response;
				$param['type'] = 'invoice.payment_succeeded';
				$stripePayment->updateInvoiceStatus($param);
				break;

				case "invoice.payment_failed":
				$param["invoice_id"] = $response->data->object->id;
				$param["invoice_status"] = $response->data->object->status;
				$param["subscription_id"] = $response->data->object->subscription;
				$param["customer_id"] = $response->data->object->customer;
				$param['response'] = $response;
				$param['type'] = 'invoice.payment_failed';
				$stripePayment->updateInvoiceStatus($param);
				break;


				case "invoice.created":
				$param = array();
				$param["invoice_id"] = $response->data->object->id;
				$param["subscription_id"] = $response->data->object->subscription;
				$param["invoice_number"] = $response->data->object->number;
				$param["customer_id"] = $response->data->object->customer;
				$param["billing_email"] = $response->data->object->customer_email;
				$param["currency"] = $response->data->object->currency;
				$param["invoice_status"] = $response->data->object->status;
				$param["invoice_created_date"] = date("Y-m-d H:i:s", $response->data->object->created);
				$param['response'] = $response;

				$i = 0;
				foreach($response->data->object->lines->data as $data)
				{
					$param["invoice_items"][$i]["amount"] = $data->amount;
					$param["invoice_items"][$i]["currency"] = $data->currency;
					$param["invoice_items"][$i]["quantity"] = $data->quantity;
					$param["invoice_items"][$i]["description"] = $data->description;
					$i++;
				}

				$stripePayment->insertInvoice($param);
				break;

				case "invoice.finalized":
				$param["invoice_id"] = $response->data->object->id;
				$param["invoice_finalized_date"] = date("Y-m-d H:i:s", $response->data->object->status_transitions->finalized_at);
				$param["invoice_status"] = $response->data->object->status;
				$param['response'] = $response;
				$stripePayment->updateInvoice($param);
				break;

				case "invoice.updated":
				$param["invoice_id"] = $response->data->object->id;
				$param["invoice_status"] = $response->data->object->status;
				$param['response'] = $response;
				$stripePayment->updateInvoice($param);
				break;

				/*for paying manual invoice*/
				case "invoice.paid":
				$param["invoice_id"] = $response->data->object->id;
				$param["invoice_status"] = $response->data->object->status;
				$param['response'] = $response;
				$param['type'] = 'invoice.paid';
				$stripePayment->updateInvoiceStatus($param);
				break;
			}
		}
	}
	
}