<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Request;
use URL;
use Auth;
use App\SampleDashboard;
use App\SemrushUserAccount;

class FooterComposer
{
	public function compose(View $view) {
		$data = SampleDashboard::first();
  		$dashboard_data = SemrushUserAccount::select('id','share_key')->where('id',$data->request_id)->first();
  		$link = \config('app.base_url').'project-detail/'.$dashboard_data->share_key;
		$view->with(['link'=> $link]);
	}
}