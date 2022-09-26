<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CampaignNote;


class CampaignNotesController extends Controller {

	public function ajax_create_campaign_notes(Request $request){
		$response = array();
		$notes = CampaignNote::create([
			'request_id'=>$request->campaign_id,
			'note_date'=>date('Y-m-d',strtotime($request->date)),
			'note'=>$request->note
		]);
		if($notes){
			$response['status'] = 1;
		}else{
			$response['status'] = 0;			
		}
		return response()->json($response);
	}

	public function ajax_get_campaign_notes(Request $request){
		$notes = CampaignNote::where('request_id',$request->campaign_id)->orderBy('note_date','desc')->get();
		$html = ''; $response = array();
		if(isset($notes) && !empty($notes)){
			foreach($notes as $key=>$value){
				$html .='<article><p>'.$value->note.'</p><cite>'.date('M d, Y',strtotime($value->note_date)).'</cite><a class="note-close popup-close articleRemove" href="javascript:;" data-id='.$value->id.'></a></article>';
			}

			$response['status'] = 1;
			$response['html'] = $html;

		}else{
			$response['status'] = 0;
			$response['html'] = $html;
		}
		return response()->json($response);
	}

	public function ajax_remove_campaign_note(Request $request){
		$response = array();
		$delete = CampaignNote::where('id',$request->note_id)->where('request_id',$request->campaign_id)->delete();
		if($delete){
			$response['status'] = 1;
		}else{
			$response['status'] = 0;
		}

		return response()->json($response);
	}
}