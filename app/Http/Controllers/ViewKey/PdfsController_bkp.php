<?php

namespace App\Http\Controllers\ViewKey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SemrushUserAccount;
use Crypt;
use App\User;
use App\CampaignDashboard;
use App\DashboardType;
use App\ModuleByDateRange;
use App\ProjectCompareGraph;
use App\BacklinkSummary;
use App\Http\Controllers\Vendor\CampaignDetailController;
use App\Http\Controllers\Vendor\LiveKeywordController;
use Http;
use URL;

class PdfsController extends Controller
{
	public function index($key = null){


		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$seo_content = CampaignDetailController::seo_content($domain_name,$campaign_id);
		$ppc_content = CampaignDetailController::ppc_content($domain_name,$campaign_id);


		$summary = $seo_content['summary'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$moz_data = $seo_content['moz_data'];
		$first_moz = $seo_content['first_moz'];
		$getGoogleAds = $ppc_content['getGoogleAds'];
		$account_id = $ppc_content['account_id'];

		$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();

		$live_keywords = LiveKeywordController::ajax_live_keyword_listpdf('All',$campaign_id,'currentPosition','asc','','');
		
		return view('viewkey.pdf.dashboard',compact('user_id','campaign_id','key','all_dashboards','types','data','users','baseUrl','summary','selectedSearch','selected','comparison','backlink_profile_summary','moz_data','first_moz','getGoogleAds','account_id','live_keywords'));

	}

	public function ppcindex($key = null){


		$encription = base64_decode($key);
		$encrypted_id = explode('-|-',$encription);
		$campaign_id = $encrypted_id[0];
		$user_id = $encrypted_id[1];

		dd($key);
		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
		$users = User::where('id',$user_id)->first();
		$all_dashboards = DashboardType::where('status',1)->pluck('name','id')->all();
		$baseUrl =  'https://' . $users->company_name . '.' . \config('app.DOMAIN_NAME');
		$domain_name = $users->company_name;
		$seo_content = CampaignDetailController::seo_content($domain_name,$campaign_id);
		$ppc_content = CampaignDetailController::ppc_content($domain_name,$campaign_id);


		$summary = $seo_content['summary'];
		$selectedSearch = $seo_content['selectedSearch'];
		$selected = $seo_content['selected'];
		$comparison = $seo_content['comparison'];
		$backlink_profile_summary = $seo_content['backlink_profile_summary'];
		$moz_data = $seo_content['moz_data'];
		$getGoogleAds = $ppc_content['getGoogleAds'];
		$account_id = $ppc_content['account_id'];

		$types = CampaignDashboard::
			where('user_id',$user_id)
			->where('status',1)
			->where('request_id',$campaign_id)
			->orderBy('order_status','asc')
			->orderBy('dashboard_id','asc')
			->pluck('dashboard_id')
			->all();

		/*$endpoint = config('app.base_url').'ajax_fetch_ads_campaign_data?account_id='.$account_id.'&campaign_id='.$campaign_id.'&column_name=impressions&order_type=desc&limit=20&page=1';*/
		$endpoint = config('app.base_url').'ppc_summary_conversion_rate_graph?account_id='.$account_id.'&campaign_id='.$campaign_id.'&column_name=impressions&order_type=desc&limit=20&page=1';

			$curl = curl_init();

			curl_setopt_array($curl, array(
			    CURLOPT_URL => $endpoint,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING => "",
			    CURLOPT_TIMEOUT => 30000,
			    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST => "GET",
			    CURLOPT_HTTPHEADER => array(

			    ),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);




		return view('viewkey.pdf.dashboard1',compact('user_id','campaign_id','key','all_dashboards','types','data','users','baseUrl','summary','selectedSearch','selected','comparison','backlink_profile_summary','moz_data','getGoogleAds','account_id'));

	}


	// public function crowdpdf($key=null,$type=null)
	// {
	// 	try {
	// 		// create the API client instance
	//         $client = new \Pdfcrowd\HtmlToPdfClient("agencydashboard", "5284d0142c46f66189276e801303c514");

	//         // configure the conversion
	//         $client->setPageSize("A4");
	//         $client->setOrientation("portrait");
	//         $client->setFooterHtml("Generated on ".date('M d,Y'));
	//         $client->setPageNumberingOffset(1);

	//         // $client->setFooterHtml('<div style="font-family: Montserrat, sans-serif;color: #3e3e3e;font-size: 10px;margin:0 -7px;"><span style="width:33%;display:inline-block;">Generated on '. date('M d,Y') .'</span><span style="width:33%;display:inline-block;"><img src="'.URL::asset('public/front/img/logo.png').'" /></span><span style="width:33%;display:inline-block;">The report data is taken from AgencyDashboard.io</span></div>');
	//         $client->setFooterHtml('<div style="font-family: Montserrat, sans-serif;color: #3e3e3e;font-size: 10px;margin:0 -7px;"> <span style="width:33%;display:inline-block;vertical-align:4px;text-align:left;">The report data is taken from <strong>AgencyDashboard.io </strong></span><span style="width:33%;display:inline-block;vertical-align:4px;text-align:center;">Generated on: '. date('M d,Y') .' </span> <span style="width:33%;display:inline-block;"><img align="right" src="https://agencydashboard.io/public/front/img/logo.png" style="margin:0;max-width:120px;" /></span></div>');

	//         $encription = base64_decode($key);
	// 		$encrypted_id = explode('-|-',$encription);
	// 		$campaign_id = $encrypted_id[0];
	// 		$user_id = $encrypted_id[1];

	// 		$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
	// 		$filname = $data->host_url.'-'.date('D-M-Y').'.pdf';
	// 		/*dd($filname);*/
	//         // run the conversion and store the result into the "pdf" variable
	//         /* $url ="https://agencydashboard.io/download/seo/eyJpdiI6ImNFY2tJUjhNN1Z1T1FDaWJ3ckhTaXc9PSIsInZhbHVlIjoiMUZYMlFXNkoyWnRSa0RSend4TTRTQT09IiwibWFjIjoiYTMwMmMzMjBjYjU3ZjY4ZTA5YWQzYTUzY2NjNjM3YzEyOWFmOTdlZjUxMDg3ZWZjYzdjZDhiOGU1NzMyMjU1MCJ9"; */
	//        	$url = "https://agencydashboard.io/download/seo/".$key;



	//         $pdf = $client->convertUrl($url);

	//         // send the result and set HTTP response headers
	//         return response($pdf)
	//             ->header('Content-Type', 'application/pdf')
	//             ->header('Cache-Control', 'no-cache')
	//             ->header('Accept-Ranges', 'none')
	//             ->header('Content-Disposition', 'attachment; filename="'.$filname.'"');
	//     }
	//     catch(\Pdfcrowd\Error $why) {
	//         // send the error in the HTTP response
	//         return response($why->getMessage(), $why->getCode())
	//             ->header('Content-Type', 'text/plain');
	//     }
	// }

	public function crowdpdf($key=null,$type=null)
	{
		try {
			// create the API client instance
	        $client = new \Pdfcrowd\HtmlToPdfClient("agencydashboard", "5284d0142c46f66189276e801303c514");

	        // configure the conversion
	        $client->setPageSize("A4");
	        $client->setOrientation("portrait");
	        $client->setFooterHtml("Generated on ".date('M d,Y'));
	        $client->setPageNumberingOffset(1);

	        $client->setFooterHtml('<div style="font-family: Montserrat, sans-serif;color: #3e3e3e;font-size: 10px;margin:0 -7px;"> <span style="width:33%;display:inline-block;vertical-align:4px;text-align:left;">The report data is taken from <strong>AgencyDashboard.io </strong></span><span style="width:33%;display:inline-block;vertical-align:4px;text-align:center;">Generated on: '. date('M d,Y') .' </span> <span style="width:33%;display:inline-block;"><img align="right" src="https://agencydashboard.io/public/front/img/logo.png" style="margin:0;max-width:120px;" /></span></div>');

	        $encription = base64_decode($key);
			$encrypted_id = explode('-|-',$encription);
			$campaign_id = $encrypted_id[0];
			$user_id = $encrypted_id[1];

			$data = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->first();
			$filname = $data->host_url.'-'.date('D-M-Y').'.pdf';



	        // run the conversion and store the result into the "pdf" variable
	        /* $url ="https://agencydashboard.io/download/seo/eyJpdiI6ImNFY2tJUjhNN1Z1T1FDaWJ3ckhTaXc9PSIsInZhbHVlIjoiMUZYMlFXNkoyWnRSa0RSend4TTRTQT09IiwibWFjIjoiYTMwMmMzMjBjYjU3ZjY4ZTA5YWQzYTUzY2NjNjM3YzEyOWFmOTdlZjUxMDg3ZWZjYzdjZDhiOGU1NzMyMjU1MCJ9"; */

	       	$url = \config('app.base_url')."download/seo/".$key;

	        $pdf = $client->convertUrl($url);

	        // send the result and set HTTP response headers
	        return response($pdf)
	            ->header('Content-Type', 'application/pdf')
	            ->header('Cache-Control', 'no-cache')
	            ->header('Accept-Ranges', 'none')
	            ->header('Content-Disposition', 'attachment; filename="'.$filname.'"');
	    }
	    catch(\Pdfcrowd\Error $why) {
	        // send the error in the HTTP response
	        return response($why->getMessage(), $why->getCode())
	            ->header('Content-Type', 'text/plain');
	    }
	}

}