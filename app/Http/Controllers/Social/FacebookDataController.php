<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use App\{User,SemrushUserAccount,Country,ModuleByDateRange,SearchConsoleUsers,DashboardType,CampaignDashboard};
use App\Http\Controllers\Vendor\CampaignDetailController;
use \Illuminate\Pagination\LengthAwarePaginator;

class FacebookDataController extends Controller {

    public function index($domain_name,$campaign_id){
        $dashboardStatus = $this->dashboardStatus('Social',$campaign_id);

        if(Auth::user() <> null){
            $user_id = User::get_parent_user_id(Auth::user()->id); //get user id from child
        }else{
            $getUser = SemrushUserAccount::where('id',$campaign_id)->first(); 
            $user_id = User::get_parent_user_id($getUser->user_id); //get user id from child
        }

        $gtUser = SemrushUserAccount::where('user_id',$user_id)->where('id',$campaign_id)->where('status',0)->first();
        
        return \View::make('vendor.campaign_detail.social',compact('campaign_id','gtUser','dashboardStatus'));
    }


    public function dashboardStatus($type,$campaign_id)
    {
        $all_dashboards = DashboardType::where('status',1)->where('name',$type)->first();
        
        if($all_dashboards <> null){
            $types = CampaignDashboard::where('dashboard_status',1)
            ->where('request_id',$campaign_id)
            ->where('dashboard_id',$all_dashboards->id)
            ->first();
            if($types <> null){
                return true;
            }else{
                return false;   
            }
        }else{
            return false;
        }
    }


    public function facebookOverview(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $graphData['status'] = 0;
        }else{
            
        $begin = strtotime(date('Y-m-d',strtotime(' -30 days')));
        $end   = strtotime(date('Y-m-d'));
        
        $likes = $this->createOverviewData('total_page_likes',$campaignId);
        $reach = $this->createOverviewData('total_impressions',$campaignId);
        $organic = $this->createOverviewData('page_organic_paid_likes',$campaignId);

        $duration = 7;
        $count = 1;
        $couter = 1;
        $organicData = 0;
        $dates = [];
        $graphDates = [];
        for($i = $begin; $i <= $end; $i = $i+86400){

            $dateData[] = date('Y-m-d',$i);
            $label = date('M d, Y',$i);
            
            $organicData += isset($organic[date('Ymd',$i)]['newsFeed']) ? $organic[date('Ymd',$i)]['newsFeed'] : 0 + isset($organic[date('Ymd',$i)]['pagesuggestions']) ? $organic[date('Ymd',$i)]['pagesuggestions'] : 0 + isset($organic[date('Ymd',$i)]['restoredLikes']) ? $organic[date('Ymd',$i)]['restoredLikes'] : 0 + isset($organic[date('Ymd',$i)]['search']) ? $organic[date('Ymd',$i)]['search'] : 0 + isset($organic[date('Ymd',$i)]['yourPage']) ? $organic[date('Ymd',$i)]['yourPage'] : 0;

            if($couter == $duration){
                $graphDates[] = $dateData;
                $dateData = [];

                $graphData['data'][] = $organicData;
                $graphData['labels'][] = $label; 
                $organicData = 0;
                $couter = 1; 
            }else{
                 $couter++;
            }

            if($count == 1){
                $graphData['likes'] = isset($likes[date('Ymd',$i)])  ?  $likes[date('Ymd',$i)] : 0;
                $graphData['reach'] = isset($reach[date('Ymd',$i)]) ? $reach[date('Ymd',$i)]  : 0;
            }else{
                $graphData['likes'] += isset($likes[date('Ymd',$i)])  ?  $likes[date('Ymd',$i)] : 0;
                $graphData['reach'] += isset($reach[date('Ymd',$i)]) ? $reach[date('Ymd',$i)] : 0;
            }
          
            $count++;
        }

        if($couter <> $duration){
            $graphData['likes'] = $this->shortNumber($graphData['likes']);
            $graphData['reach'] = $this->shortNumber($graphData['reach']);
            $graphDates[] = $dateData;
            $graphData['data'][] = $organicData;
            $graphData['labels'][] = $label; 
        }
        
        }

        return $graphData;
        // return response()->json($graphData);
    }

    public function createOverviewData($fileName,$campaignId){
        $url = env('FILE_PATH')."public/facebook/".$campaignId.'/'.$fileName.'.json';
        $data = file_get_contents($url);
        $final = json_decode($data);
        $array = json_decode(json_encode($final), true); 

       return $array;
    }

    public function getDates($request){
        if(!empty($request->campaignData['viewkey'])){
            if($request->campaignData['viewkey'] == 1){
                $facebook = array(
                    'duration' => $request->campaignData['selected_label'],
                    'start_date' => $request->campaignData['current_start'],
                    'end_date' => $request->campaignData['current_end'],
                    'compare_start_date' => $request->campaignData['previous_start'],
                    'compare_end_date' => $request->campaignData['previous_end'],
                    'comparison' => $request->campaignData['comparison'],
                    'compare_to' => $request->campaignData['comparison_selected']
                );
            }
        }else{
            $facebook = $this->facebookDateRangeSelection($request);
        }

        return $facebook;
    }

    public function getFbLikes(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            
            $facebook = $this->getDates($request);

            $begin = strtotime($facebook['start_date']);
            $end   = strtotime($facebook['end_date']);

            $comparsionBegin = strtotime($facebook['compare_start_date']);
            $comparsionEnd = strtotime($facebook['compare_end_date']);

            $res['selected'] = $facebook['duration'];
            $res['start_date'] = $facebook['start_date'];
            $res['end_date'] = $facebook['end_date'];
            $res['compare_start_date'] = $facebook['compare_start_date'];
            $res['compare_end_date'] = $facebook['compare_end_date'];
            $res['comparison'] = $facebook['comparison'];
            $res['compare_to'] = $facebook['compare_to'];

            // $res['social_range'] = date('M d Y',strtotime($facebook['start_date'])).' - '.date('M d Y',strtotime($facebook['end_date']));
            $res['social_range'] = '<p class="facebook_comparison_dates common_compare_date"><b><span class="range-key">'.$facebook['duration'].' </span>'.date('M d Y',strtotime($facebook['start_date'])).' - '.date('M d Y',strtotime($facebook['end_date'])).'</b><span> </span></p>';
            if($facebook['comparison'] == 1){
                $res['social_range'] = '<p class="facebook_comparison_dates common_compare_date"><b><span class="range-key">'.$facebook['duration'].' </span>'.date('M d Y',strtotime($facebook['start_date'])).' - '.date('M d Y',strtotime($facebook['end_date'])).'</b> <span> Compare: '.date('M d Y',strtotime($facebook['compare_start_date'])).' - '.date('M d Y',strtotime($facebook['compare_end_date'])).'</span></p>';
                // $res['social_range'] = date('M d Y',strtotime($facebook['start_date'])).' - '.date('M d Y',strtotime($facebook['end_date'])).' (compared to '.date('M d Y',strtotime($facebook['compare_start_date'])).' - '.date('M d Y',strtotime($facebook['compare_end_date'])).')';
            }

            $current = $this->setLikesData('total_page_likes',$campaignId,$begin,$end);
            $previous = $this->setLikesData('total_page_likes',$campaignId,$comparsionBegin,$comparsionEnd);

            // echo "<pre>";
            // print_r($current);
            // echo "<pre>";
            // print_r($previous);
            // die;
            
            $subtract = $current - $previous;
            $class = '';
            if($previous == 0){
                $percentCalculate = 0;
            }else{
                $percentCalculate = ($subtract / $previous) * 100;
                $class = 'ion-arrow-up-a green';
                if (str_contains($percentCalculate, '-')) { 
                    $class = 'ion-arrow-down-a red';
                    $percentCalculate = str_replace( '-', '', $percentCalculate);
                }
            }
            
            $res['pagelikes'] = $this->shortNumber($current);
            $res['pagelikespercent'] = $this->shortNumber($percentCalculate).'%';
            $res['fbLikeclass'] = $class;
        }
        return response()->json($res);
    }

    public function setLikesData($fileName,$campaignId,$begin,$end){
        $likes = $this->createOverviewData($fileName,$campaignId);
        $count = 0;
        for($i = $begin; $i <= $end; $i = $i+86400){
            $count+= isset($likes[date('Ymd',$i)]) ? $likes[date('Ymd',$i)] : 0; 
        }
        return $count;
    }


    public function getFbOrganicPaidLikes(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $graphData['status'] = 0;
        }else{

            $facebook = $this->getDates($request);
           
            $begin = strtotime($facebook['start_date']);
            $end   = strtotime($facebook['end_date']);

            $comparsionBegin = strtotime($facebook['compare_start_date']);
            $comparsionEnd = strtotime($facebook['compare_end_date']);

            $organicPaid = $this->createOverviewData('page_organic_paid_likes',$campaignId);
            $graphData['comparison'] = $facebook['comparison'];

            $organicCount = 0;
            $paidCount = 0;
            for($i = $begin; $i <= $end; $i = $i+86400){

                $organicCount +=  @$organicPaid[date('Ymd',$i)]['newsFeed'] + @$organicPaid[date('Ymd',$i)]['pagesuggestions'] + @$organicPaid[date('Ymd',$i)]['restoredLikes'] + @$organicPaid[date('Ymd',$i)]['search'] + @$organicPaid[date('Ymd',$i)]['yourPage'];
                $paidCount += isset($organicPaid[date('Ymd',$i)]['ads']) ? $organicPaid[date('Ymd',$i)]['ads'] : 0;

                $graphData['organic'][]  = @$organicPaid[date('Ymd',$i)]['newsFeed'] + @$organicPaid[date('Ymd',$i)]['pagesuggestions'] + @$organicPaid[date('Ymd',$i)]['restoredLikes'] + @$organicPaid[date('Ymd',$i)]['search'] + @$organicPaid[date('Ymd',$i)]['yourPage'];
                $graphData['paid'][]   = isset($organicPaid[date('Ymd',$i)]['ads']) ? $organicPaid[date('Ymd',$i)]['ads'] : 0;
                $graphData['labels'][]  = date('M d, Y',$i);
            }

            for($i = $comparsionBegin; $i <= $comparsionEnd; $i = $i+86400){
                $graphData['previous_organic'][]  = @$organicPaid[date('Ymd',$i)]['newsFeed'] + @$organicPaid[date('Ymd',$i)]['pagesuggestions'] + @$organicPaid[date('Ymd',$i)]['restoredLikes'] + @$organicPaid[date('Ymd',$i)]['search'] + @$organicPaid[date('Ymd',$i)]['yourPage'];
                $graphData['previous_paid'][]   = isset($organicPaid[date('Ymd',$i)]['ads']) ? $organicPaid[date('Ymd',$i)]['ads'] : 0;
                $graphData['previous_labels'][]  = date('M d, Y',$i);
            }

            $graphData['organicCount'] = $organicCount;
            $graphData['paidCount'] = $paidCount;
            $graphData['data'] = [$organicCount,$paidCount];
            $graphData['total'] = [$this->shortNumber($organicCount + $paidCount)];
        }
        return response()->json($graphData);
    }


    public function getFbGenderLikes(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res = $this->manageGenderData('page_gender_likes',$request);
        }
        return response()->json($res);
    }
	
    public function manageGenderData($fileName,$request){
        $campaignId = $request->id;
        $gender = $this->createOverviewData($fileName,$campaignId);

        $facebook = $this->getDates($request);

        $begin = strtotime($facebook['start_date']);
        $end   = strtotime($facebook['end_date']);

        $comparsionBegin = strtotime($facebook['compare_start_date']);
        $comparsionEnd = strtotime($facebook['compare_end_date']);
      
        for($i = $begin; $i <= $end; $i = $i+86400){
            $res['labels'][] = date('M d, Y',$i);
            $res['male'][]   = @$gender[date('Ymd',$i)]['M.13-17'] + @$gender[date('Ymd',$i)]['M.18-24'] + @$gender[date('Ymd',$i)]['M.25-34'] + @$gender[date('Ymd',$i)]['M.35-44'] + @$gender[date('Ymd',$i)]['M.45-54'] + @$gender[date('Ymd',$i)]['M.55-64'] + @$gender[date('Ymd',$i)]['M.65+'];
            $res['female'][]   = @$gender[date('Ymd',$i)]['F.13-17'] + @$gender[date('Ymd',$i)]['F.18-24'] + @$gender[date('Ymd',$i)]['F.25-34'] + @$gender[date('Ymd',$i)]['F.35-44'] + @$gender[date('Ymd',$i)]['F.45-54'] + @$gender[date('Ymd',$i)]['F.55-64'] + @$gender[date('Ymd',$i)]['F.65+'];
            $res['other'][]   = @$gender[date('Ymd',$i)]['U.13-17'] + @$gender[date('Ymd',$i)]['U.18-24'] + @$gender[date('Ymd',$i)]['U.25-34'] + @$gender[date('Ymd',$i)]['U.35-44'] + @$gender[date('Ymd',$i)]['U.45-54'] + @$gender[date('Ymd',$i)]['U.55-64'] + @$gender[date('Ymd',$i)]['U.65+'];

            if(!empty($gender[date('Ymd',$i)])){
                $lastkey = date('Ymd',$i);
            }
        }


        for($i = $comparsionBegin; $i <= $comparsionEnd; $i = $i+86400){
            $res['previous_labels'][] = date('M d, Y',$i);
            $res['previous_male'][]   = @$gender[date('Ymd',$i)]['M.13-17'] + @$gender[date('Ymd',$i)]['M.18-24'] + @$gender[date('Ymd',$i)]['M.25-34'] + @$gender[date('Ymd',$i)]['M.35-44'] + @$gender[date('Ymd',$i)]['M.45-54'] + @$gender[date('Ymd',$i)]['M.55-64'] + @$gender[date('Ymd',$i)]['M.65+'];
            $res['previous_female'][]   = @$gender[date('Ymd',$i)]['F.13-17'] + @$gender[date('Ymd',$i)]['F.18-24'] + @$gender[date('Ymd',$i)]['F.25-34'] + @$gender[date('Ymd',$i)]['F.35-44'] + @$gender[date('Ymd',$i)]['F.45-54'] + @$gender[date('Ymd',$i)]['F.55-64'] + @$gender[date('Ymd',$i)]['F.65+'];
            $res['previous_other'][]   = @$gender[date('Ymd',$i)]['U.13-17'] + @$gender[date('Ymd',$i)]['U.18-24'] + @$gender[date('Ymd',$i)]['U.25-34'] + @$gender[date('Ymd',$i)]['U.35-44'] + @$gender[date('Ymd',$i)]['U.45-54'] + @$gender[date('Ymd',$i)]['U.55-64'] + @$gender[date('Ymd',$i)]['U.65+'];

            if(!empty($gender[date('Ymd',$i)])){
                $previouslastkey = date('Ymd',$i);
            }
        }


        $lastArray = isset($lastkey) ? $gender[$lastkey] : 0;
        $age1317 = @$lastArray['U.13-17'] + @$lastArray['F.13-17'] + @$lastArray['M.13-17'];
        $age1824 = @$lastArray['U.18-24'] + @$lastArray['F.18-24'] + @$lastArray['M.18-24'];
        $age2534 = @$lastArray['U.25-34'] + @$lastArray['F.25-34'] + @$lastArray['M.25-34'];
        $age3534 = @$lastArray['U.35-44'] + @$lastArray['F.35-44'] + @$lastArray['M.35-44'];
        $age4554 = @$lastArray['U.45-54'] + @$lastArray['F.45-54'] + @$lastArray['M.45-54'];
        $age5564 = @$lastArray['U.55-64'] + @$lastArray['F.55-64'] + @$lastArray['M.55-64'];
        $age65   = @$lastArray['U.65+']   + @$lastArray['F.65+']   + @$lastArray['M.65+'];

        $maleCount = @$lastArray['M.13-17'] + @$lastArray['M.18-24'] + @$lastArray['M.25-34'] + @$lastArray['M.35-44'] + @$lastArray['M.45-54'] + @$lastArray['M.55-64'] + @$lastArray['M.65+'];
        $femaleCount = @$lastArray['F.13-17'] + @$lastArray['F.18-24'] + @$lastArray['F.25-34'] + @$lastArray['F.35-44'] + @$lastArray['F.45-54'] + @$lastArray['F.55-64'] + @$lastArray['F.65+'];
        $otherCount = @$lastArray['U.13-17'] + @$lastArray['U.18-24'] + @$lastArray['U.25-34'] + @$lastArray['U.35-44'] + @$lastArray['U.45-54'] + @$lastArray['U.55-64'] + @$lastArray['U.65+'];


        $previouslastArray = isset($previouslastkey) ? $gender[$previouslastkey] : 0;
        $previousage1317 = @$previouslastArray['U.13-17'] + @$previouslastArray['F.13-17'] + @$previouslastArray['M.13-17'];
        $previousage1824 = @$previouslastArray['U.18-24'] + @$previouslastArray['F.18-24'] + @$previouslastArray['M.18-24'];
        $previousage2534 = @$previouslastArray['U.25-34'] + @$previouslastArray['F.25-34'] + @$previouslastArray['M.25-34'];
        $previousage3534 = @$previouslastArray['U.35-44'] + @$previouslastArray['F.35-44'] + @$previouslastArray['M.35-44'];
        $previousage4554 = @$previouslastArray['U.45-54'] + @$previouslastArray['F.45-54'] + @$previouslastArray['M.45-54'];
        $previousage5564 = @$previouslastArray['U.55-64'] + @$previouslastArray['F.55-64'] + @$previouslastArray['M.55-64'];
        $previousage65   = @$previouslastArray['U.65+']   + @$previouslastArray['F.65+']   + @$previouslastArray['M.65+'];

        $previousmaleCount = @$previouslastArray['M.13-17'] + @$previouslastArray['M.18-24'] + @$previouslastArray['M.25-34'] + @$previouslastArray['M.35-44'] + @$previouslastArray['M.45-54'] + @$previouslastArray['M.55-64'] + @$previouslastArray['M.65+'];
        $previousfemaleCount = @$previouslastArray['F.13-17'] + @$previouslastArray['F.18-24'] + @$previouslastArray['F.25-34'] + @$previouslastArray['F.35-44'] + @$previouslastArray['F.45-54'] + @$previouslastArray['F.55-64'] + @$previouslastArray['F.65+'];
        $previousotherCount = @$previouslastArray['U.13-17'] + @$previouslastArray['U.18-24'] + @$previouslastArray['U.25-34'] + @$previouslastArray['U.35-44'] + @$previouslastArray['U.45-54'] + @$previouslastArray['U.55-64'] + @$previouslastArray['U.65+'];



        $res['maleCount']   = $maleCount;
        $res['femaleCount'] = $femaleCount;
        $res['otherCount']  = $otherCount;
        $res['data']        = [$maleCount,$femaleCount,$otherCount];
        $res['total']       = $this->shortNumber($maleCount + $femaleCount + $otherCount);
        $res['age']  = [$age1317,$age1824,$age2534,$age3534,$age4554,$age5564,$age65];

        $res['previousmaleCount']   = $previousmaleCount;
        $res['previousfemaleCount'] = $previousfemaleCount;
        $res['previousotherCount']  = $previousotherCount;
        $res['previousage']  = [$previousage1317,$previousage1824,$previousage2534,$previousage3534,$previousage4554,$previousage5564,$previousage65];
        $res['comparison'] = $facebook['comparison'];
        return $res;
    }

    public function getFbCountryLikes(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res['data'] = $this->manageCountryData('page_country_likes',$campaignId,$pdfStatus);
        }
        return response()->json($res);
    }


    public function manageCountryData($fileName,$campaignId,$pdfStatus){
        $country = $this->createOverviewData($fileName,$campaignId);
        $lastArray = end($country);
        $countries = Country::pluck('countries_name','short_code')->all();
       
        $createTableview = '';
        if(!empty($lastArray)){
            uasort($lastArray, function($a, $b) {
                return $b - $a;
            });
            $i = 0;
            $res['count'] = 1;
            foreach ($lastArray as $key => $value) {
                if (array_key_exists($key,$countries)){
                    $flagKey = Str::lower($key);
                    $createTableview.= '<tr><td><span><img src="'.url('public/flags/'.$flagKey.'.png').'" alt="'.$flagKey.'">'.$countries[$key].'</span></td><td>'.number_format($value).'</td></tr>';
                }else{
                    $createTableview.= '<tr><td><span><img src="'.url('public/flags/'.$flagKey.'.png').'" alt="'.$flagKey.'">'.$key.'</span></td><td>'.number_format($value).'</td></tr>';
                }
                //when create pdf add limit
                if($pdfStatus == 1){
                    $i++;
                    if($i >= 27){
                        break;
                    }
                }    

            }
        }else{
            $res['count'] = 0;
            $createTableview = '<tr class="no_record"><td>No data matching the selected criteria.</td></tr>';
        }
        $res['data'] = $createTableview;
       return  $res;
    }

    public function getFbCityLikes(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res['data'] = $this->manageCityData('page_city_likes',$campaignId,$pdfStatus);
        }
        return response()->json($res);
    }

    public function manageCityData($fileName,$campaignId,$pdfStatus){
        $city = $this->createOverviewData($fileName,$campaignId);
        $lastArray = end($city);
        
        $createTableview = '';
        if(!empty($lastArray)){
            uasort($lastArray, function($a, $b) {
                return $b - $a;
            });
            $i=0;
            $res['count'] = 1;
            foreach ($lastArray as $key => $value) {
                $createTableview.= '<tr><td>'.$key.'</td><td>'.number_format($value).'</td></tr>';
                
                //when create pdf add limit
                if($pdfStatus == 1){
                    $i++;
                    if($i >= 27){
                        break;
                    }
                }   
            }
        }else{
            $res['count'] = 0;
            $createTableview = '<tr class="no_record"><td>No data matching the selected criteria.</td></tr>';
        }
        $res['data'] = $createTableview;
        return $res;
    }

    public function getFbLanguageLikes(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res['data'] = $this->manageLanguageData('page_language_likes',$campaignId,$pdfStatus);
        }
        return response()->json($res);
    }

    public function manageLanguageData($fileName,$campaignId,$pdfStatus){
        $language = $this->createOverviewData($fileName,$campaignId);
        $lastArray = end($language);
        $countries = Country::pluck('countries_name','short_code')->all();

        $createTableview = '';
        if(!empty($lastArray)){
            uasort($lastArray, function($a, $b) {
                return $b - $a;
            });
            $i=0;
            $res['count'] = 1;
            foreach ($lastArray as $key => $value) {
                $locale = explode('_', $key);
                if (array_key_exists($locale[1],$countries)){
                    // $createTableview.= '<tr><td>'.locale_get_display_language($key).' ('.locale_get_region($key).') ('.$countries[$locale[1]].')</td><td>'.number_format($value).'</td></tr>';
                    $createTableview.= '<tr><td>'.$key.' ('.$countries[$locale[1]].')</td><td>'.number_format($value).'</td></tr>';
                }else{
                    $createTableview.= '<tr><td>'.$key.'</td><td>'.number_format($value).'</td></tr>';
                }
                //when create pdf add limit
                if($pdfStatus == 1){
                    $i++;
                    if($i >= 27){
                        break;
                    }
                }   
            }
        }else{
            $res['count'] = 0;
            $createTableview = '<tr class="no_record"><td>No data matching the selected criteria.</td></tr>';
        }
        $res['data'] = $createTableview;
        return $res;
    }


    public function getFbCountryReach(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res['data'] = $this->manageCountryData('countrywise_impressions',$campaignId,$pdfStatus);
        }
        return response()->json($res);
    }

    public function getFbCityReach(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res['data'] = $this->manageCityData('citywise_impressions',$campaignId,$pdfStatus);
        }
        return response()->json($res);
    }

    public function getFbLanguageReach(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res['data'] = $this->manageLanguageData('localewise_impressions',$campaignId,$pdfStatus);
        }
        return response()->json($res);
    }

    public function getFbOrganicPaidReach(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $facebook = $this->getDates($request);
           
            $begin = strtotime($facebook['start_date']);
            $end   = strtotime($facebook['end_date']);

            $organic = $this->createOverviewData('organic_impressions',$campaignId);
            $paid = $this->createOverviewData('paid_impressions',$campaignId);
            
            $countOrganic = 0;
            $countPaid = 0;
            for($i = $begin; $i <= $end; $i = $i+86400){

                $countOrganic+= isset($organic[date('Ymd',$i)]) ? $organic[date('Ymd',$i)] : 0;
                $countPaid+=    isset($paid[date('Ymd',$i)]) ? $paid[date('Ymd',$i)] : 0;

            }

           $res['countOrganic'] = $countOrganic;
           $res['countPaid'] = $countPaid;
           $res['data'] =   [$countOrganic,$countPaid];
           $res['total'] =  [$this->shortNumber($countOrganic+$countPaid)];
        }
        return response()->json($res);
    }

    public function getFbOrganicPaidVideoReach(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{

            $facebook = $this->getDates($request);
           
            $begin = strtotime($facebook['start_date']);
            $end   = strtotime($facebook['end_date']);

            $organic = $this->createOverviewData('organicvideo_impressions',$campaignId);
            $paid = $this->createOverviewData('paidvideo_impressions',$campaignId);
            
            $countOrganic = 0;
            $countPaid = 0;
            for($i = $begin; $i <= $end; $i = $i+86400){

                $countOrganic+= isset($organic[date('Ymd',$i)]) ? $organic[date('Ymd',$i)] : 0;
                $countPaid+=    isset($paid[date('Ymd',$i)]) ? $paid[date('Ymd',$i)] : 0;
            }

           $res['countOrganic'] = $countOrganic;
           $res['countPaid'] = $countPaid;
           $res['data'] =   [$countOrganic,$countPaid];
           $res['total'] =  [$this->shortNumber($countOrganic+$countPaid)];
        }
        return response()->json($res);
    }
    
    public function getFbGenderReach(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $res = $this->manageGenderData('genderwise_impressions',$request);
        }
        return response()->json($res);
    }

    public function getFbPosts(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{
            $campaign = SemrushUserAccount::where('id',$campaignId)->first(); //get user id from campaignId
            $posts = $this->createOverviewData('page_posts',$campaignId);
            if(count($posts) > 0){
                $newCollection = collect($posts);
            }else{
                $result = array();
                $newCollection = collect($result);              
            }
            
            $page = request()->has('page') ? request('page') : 1;

            // Set default per page
            $perPage = request()->has('per_page') ? request('per_page') : 6;

            // Offset required to take the results
            $offset = ($page * $perPage) - $perPage;
            $results =  new LengthAwarePaginator(
                $newCollection->slice($offset, $perPage),
                $newCollection->count(),
                $perPage,
                $page
            );

            return view('vendor.social.facebook-sections.facebook-post',compact('results','pdfStatus','campaign'));
        }
    }

    public function getFbPostsPagination(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{

            $posts = $this->createOverviewData('page_posts',$campaignId);
            if(count($posts) > 0){
                $newCollection = collect($posts);
            }else{
                $result = array();
                $newCollection = collect($result);              
            }
            
            $page = request()->has('page') ? request('page') : 1;

            // Set default per page
            $perPage = request()->has('per_page') ? request('per_page') : 6;

            // Offset required to take the results
            $offset = ($page * $perPage) - $perPage;
            $results =  new LengthAwarePaginator(
                $newCollection->slice($offset, $perPage),
                $newCollection->count(),
                $perPage,
                $page
            );

            return view('vendor.social.facebook-sections.facebook-post-pagination',compact('results','pdfStatus'));
        }
    }



    public function getFbReviews(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{

            $reviews = $this->createOverviewData('page_reviews',$campaignId);
            $campaign = SemrushUserAccount::where('id',$campaignId)->first(); //get user id from campaignId
            if(count($reviews) > 0){
                $newCollection = collect($reviews);
            }else{
                $result = array();
                $newCollection = collect($result);              
            }
            
            $page = request()->has('page') ? request('page') : 1;

            // Set default per page
            $perPage = request()->has('per_page') ? request('per_page') : 6;

            // Offset required to take the results
            $offset = ($page * $perPage) - $perPage;
            $results =  new LengthAwarePaginator(
                $newCollection->slice($offset, $perPage),
                $newCollection->count(),
                $perPage,
                $page
            );

            return view('vendor.social.facebook-sections.facebook-reviews',compact('results','pdfStatus','campaign'));
        }
    }

    public function getFbReviewsPagination(Request $request){
        $campaignId = $request->id;
        $pdfStatus = $request->pdfStatus;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{

            $reviews = $this->createOverviewData('page_reviews',$campaignId);
            if(count($reviews) > 0){
                $newCollection = collect($reviews);
            }else{
                $result = array();
                $newCollection = collect($result);              
            }
            
            $page = request()->has('page') ? request('page') : 1;

            // Set default per page
            $perPage = request()->has('per_page') ? request('per_page') : 6;

            // Offset required to take the results
            $offset = ($page * $perPage) - $perPage;
            $results =  new LengthAwarePaginator(
                $newCollection->slice($offset, $perPage),
                $newCollection->count(),
                $perPage,
                $page
            );

            return view('vendor.social.facebook-sections.facebook-reviews-pagination',compact('results','pdfStatus'));
        }
    }

    public function getFbReach(Request $request){
        $campaignId = $request->id;
        if (!file_exists(env('FILE_PATH')."public/facebook/".$campaignId)) {
            $res['status'] = 0;
        }else{

            $facebook = $this->getDates($request);

            $begin = strtotime($facebook['start_date']);
            $end   = strtotime($facebook['end_date']);

            $comparsionBegin = strtotime($facebook['compare_start_date']);
            $comparsionEnd = strtotime($facebook['compare_end_date']);

       
            $res['social_range'] = '<p class="facebook_comparison_dates common_compare_date"><b><span class="range-key">'.$facebook['duration'].' </span>'.date('M d Y',strtotime($facebook['start_date'])).' - '.date('M d Y',strtotime($facebook['end_date'])).'</b><span> </span></p>';
            if($facebook['comparison'] == 1){
                $res['social_range'] = '<p class="facebook_comparison_dates common_compare_date"><b><span class="range-key">'.$facebook['duration'].' </span>'.date('M d Y',strtotime($facebook['start_date'])).' - '.date('M d Y',strtotime($facebook['end_date'])).'</b> <span> Compare: '.date('M d Y',strtotime($facebook['compare_start_date'])).' - '.date('M d Y',strtotime($facebook['compare_end_date'])).'</span></p>';
            }

            $reach = $this->createOverviewData('total_impressions',$campaignId);
            
            $count = 0;
            for($i = $begin; $i <= $end; $i = $i+86400){
                $count+= isset($reach[date('Ymd',$i)]) ? $reach[date('Ymd',$i)] : 0;
                $res['labels'][] = date('M d, Y',$i);
                $res['data'][] = isset($reach[date('Ymd',$i)]) ? $reach[date('Ymd',$i)] : 0;
            }

            $previousCount = 0;
            for($i = $comparsionBegin; $i <= $comparsionEnd; $i = $i+86400){
                $previousCount+= isset($reach[date('Ymd',$i)]) ? $reach[date('Ymd',$i)] : 0;
                $res['previous_labels'][] = date('M d, Y',$i);
                $res['previous_data'][] = isset($reach[date('Ymd',$i)]) ? $reach[date('Ymd',$i)] : 0;
            }

            $res['comparison'] = $facebook['comparison'];
            $res['count'] = $this->shortNumber($count);
        }
        return response()->json($res);
    }


    public function shortNumber($num) 
    {
        if ($num > 1000000000000) return round(($num/1000000000000), 2).' T';
        elseif ($num > 1000000000) return round(($num/1000000000), 2).' B';
        elseif ($num > 1000000) return round(($num/1000000), 2).' M';
        elseif ($num > 1000) return round(($num/1000), 2).' K';

        return number_format($num);
    }

    public function facebookDateRangeSelection($request){
        $selected = 'Three Month';
        $endDate = date('Y-m-d',strtotime('-1 day'));
        $startDate = date('Y-m-d',strtotime('-3 month',strtotime($endDate)));
        $comparisonPeriod = 'previous_period'; $comparison = 0;

        $moduleSearchStatus = ModuleByDateRange::getModuleDateRange($request->id,'facebook');
        if(!empty($moduleSearchStatus)){

            $comparison = $moduleSearchStatus->status;
            $comparisonPeriod = $moduleSearchStatus->comparison;
            $duration = $moduleSearchStatus->duration;

            if($duration == 1){
                $selected = 'One Month';
                $startDate = date('Y-m-d', strtotime("-1 month", strtotime($endDate)));
            }elseif($duration == 3){
                $selected = 'Three Month';
                $startDate = date('Y-m-d', strtotime("-3 month", strtotime($endDate)));
            }elseif($duration == 6){
                $selected = 'Six Month';
                $startDate = date('Y-m-d', strtotime("-6 month", strtotime($endDate)));
            }elseif($duration == 9){
                $selected = 'Nine Month';
                $startDate = date('Y-m-d', strtotime("-9 month", strtotime($endDate)));
            }elseif($duration == 12){
                $selected = 'One Year';
                $startDate = date('Y-m-d', strtotime("-1 year", strtotime($endDate)));
            }elseif($duration == 24){
                $selected = 'Two Year';
                $startDate = date('Y-m-d', strtotime("-2 year", strtotime($endDate)));
            }
        }

        if($comparisonPeriod === 'previous_period'){
            $calculatedDuration = ModuleByDateRange::calculate_days($startDate,$endDate);
            $previousPeriodDates = SearchConsoleUsers::calculate_previous_period($startDate,$calculatedDuration);
        }else{
            $previousPeriodDates = SearchConsoleUsers::calculate_previous_year($startDate,$endDate);    
        }
        $prevStartDate = $previousPeriodDates['previous_start_date'];
        $prevEndDate = $previousPeriodDates['previous_end_date'];


        if(!empty($request->campaignData['selected_label'])){
            if($request->campaignData['selected_label'] == 'Custom Range'){
                $selected   = $request->campaignData['selected_label'];
                $startDate  = $request->campaignData['current_start'];
                $endDate    = $request->campaignData['current_end'];
                $prevStartDate    = $request->campaignData['previous_start'];
                $prevEndDate    = $request->campaignData['previous_end'];
                $comparison    = $request->campaignData['comparison'];
                $comparisonPeriod    = $request->campaignData['comparison_selected'];
            }
        }

        $final = array(
            'duration' => $selected,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'compare_start_date' => $prevStartDate,
            'compare_end_date' => $prevEndDate,
            'comparison' => $comparison,
            'compare_to' => $comparisonPeriod
        );
        return $final;
    }

    public function getFBsearch(Request $request){
        try{
            
            // $userId = User::get_parent_user_id(Auth::user()->id); //get user id from child
            $userId = SemrushUserAccount::where('id',$request->campaignId)->first(); //get user id from campaignId
            $selectedDuration = $request->selected_label;
            switch ($selectedDuration) {
              case "One Month":
                $duration = 1;
                break;
              case "Three Month":
                $duration = 3;
                break;
              case "Six Month":
                $duration = 6;
                break;
              case "Nine Month":
                $duration = 9;
                break;
              case "One Year":
                $duration = 12;
                break;
              case "Two Year":
                $duration = 24;
                break;
              default:
                $duration = 0;
            }

            if($request->viewkey == 0){
                if($duration != 0){
                    $id=array('module' => $request->module, 'request_id' => $request->campaignId, 'user_id' => $userId->user_id);
                    $query = ModuleByDateRange::updateOrCreate($id,[
                        'user_id' => $userId->user_id,
                        'request_id'=> $request->campaignId,
                        'duration'=> $duration,
                        'module'=>$request->module,
                        'start_date'=>$request->current_start,
                        'end_date'=>$request->current_end,
                        'compare_start_date'=>$request->previous_start,
                        'compare_end_date'=>$request->previous_end,
                        'status'=>$request->comparison,
                        'comparison'=>$request->comparison_selected
                    ]);
                }
            }
            
            $res['status'] = 1;
            $res['message'] = 'sucess';
            return response()->json($res);
        }catch(\Exception $e){
            $res['status'] = 0;
            $res['message'] = $e->getMessage();
            return response()->json($res);
        }
    }

    public function socialFilters(Request $request){
        $html = '<div class="filter-list fboverviewfilter"><p class="overview_date_range"><span uk-icon="clock"></span> Last 30 Days</p></div>';
        return response()->json($html);
    }

    public function socialDateRangeFilters(Request $request){
        if(!empty($request->campaignData['viewkey'])){
            if($request->campaignData['viewkey'] == 1){
                $facebook = array(
                    'duration' => $request->campaignData['selected_label'],
                    'start_date' => $request->campaignData['current_start'],
                    'end_date' => $request->campaignData['current_end'],
                    'compare_start_date' => $request->campaignData['previous_start'],
                    'compare_end_date' => $request->campaignData['previous_end'],
                    'comparison' => $request->campaignData['comparison'],
                    'compare_to' => $request->campaignData['comparison_selected']
                );
            }
        }else{
            $facebook = $this->facebookDateRangeSelection($request);
        }
        

        $begin = strtotime($facebook['start_date']);
        $end   = strtotime($facebook['end_date']);

        $comparsionBegin = strtotime($facebook['compare_start_date']);
        $comparsionEnd = strtotime($facebook['compare_end_date']);

        $res['selected'] = $facebook['duration'];
        $res['start_date'] = $facebook['start_date'];
        $res['end_date'] = $facebook['end_date'];
        $res['compare_start_date'] = $facebook['compare_start_date'];
        $res['compare_end_date'] = $facebook['compare_end_date'];
        $res['comparison'] = $facebook['comparison'];
        $res['compare_to'] = $facebook['compare_to'];
       
        $attr = 'disabled';
        $attrchecked = '';
        $previousStartDate = '';
        $previousEndDate = '';
        if($facebook['comparison'] == 1){
            $attr = '';
            $attrchecked = 'checked';
            $previousStartDate = $facebook['compare_start_date'];
            $previousEndDate = $facebook['compare_end_date'];
        }

        $previousYearAttr = '';
        $previousPeriodAttr = '';
        if($facebook['compare_to'] == 'previous_year'){
            $previousYearAttr = 'selected';
        }else{
            $previousPeriodAttr = 'selected';
        }

        if(!empty($request->campaignData['selected'])){
            if($request->campaignData['selected'] == '#facebookviewlikes'){
                $html='<div class="filter-list fbpagefilter"><ul><li><a href="javascript:;" id="facebookLikes_dateRange" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center"><img src="'.url('public/vendor/internal-pages/images/date-rance-calender-icon.png').'"></a></li></ul></div><div class="dateRange-popup" id="facebook-likes-dateRange-popup"><form><div class="dateRange-fields"><div class="form-group uk-flex"><label>Date Range</label></div><div class="form-group uk-flex"><div id="facebook_likes_current_range" class="form-control rangepicker facebook_daterangepicker"><input type="hidden" class="facebook_likes_start_date" value="'.$facebook['start_date'].'"><input type="hidden" class="facebook_likes_end_date" value="'.$facebook['end_date'].'"><input type="hidden" class="facebook_likes_current_label" value="'.$facebook['duration'].'"><input type="hidden" class="facebook_likes_comparison_days"><i class="fa fa-calendar"></i><p></p></div></div><div class="form-group uk-flex"><input type="hidden" class="facebook_likes_is_compare"><label class="sw"><input type="checkbox"  class="facebook_likes_compare" '.$attrchecked.' ><div class="sw-pan"></div><div class="sw-btn"></div></label><label>Compare to:</label><select class="form-control" id="facebook_likes_comparison" '.$attr.'><option class="facebook_likes_previous_period" selected="selected" value="previous_period" '.$previousPeriodAttr.'>Previous period</option><option class="facebook_likes_previous_year" value="previous_year" '.$previousYearAttr.'>Previous year</option></select></div><div class="form-group uk-flex" id="facebook_likes_previous-section"><div id="facebook_likes_previous_range" class="form-control rangepicker facebook_daterangepicker"><input type="hidden" class="facebook_likes_prev_start_date" value="'.$previousStartDate.'"><input type="hidden" class="facebook_likes_prev_end_date" value="'.$previousEndDate.'"><input type="hidden" class="facebook_likes_prev_comparison_days"><i class="fa fa-calendar"></i><p></p></div></div><div class="uk-flex"><input type="button" class="btn blue-btn facebook_likes_apply_btn" value="Apply" ><a href="javascript:;" class="facebook_likes_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a><div></div></form></div>';
            }

            if($request->campaignData['selected'] == '#facebookviewreach'){
                $html='<div class="filter-list fbpagefilter"><ul><li><a href="javascript:;" id="facebookReach_dateRange" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center"><img src="'.url('public/vendor/internal-pages/images/date-rance-calender-icon.png').'"></a></li></ul></div><div class="dateRange-popup" id="facebook-reach-dateRange-popup"><form><div class="dateRange-fields"><div class="form-group uk-flex"><label>Date Range</label></div><div class="form-group uk-flex"><div id="facebook_reach_current_range" class="form-control rangepicker facebook_daterangepicker"><input type="hidden" class="facebook_reach_start_date" value="'.$facebook['start_date'].'"><input type="hidden" class="facebook_reach_end_date" value="'.$facebook['end_date'].'"><input type="hidden" class="facebook_reach_current_label" value="'.$facebook['duration'].'"><input type="hidden" class="facebook_reach_comparison_days"><i class="fa fa-calendar"></i><p></p></div></div><div class="form-group uk-flex"><input type="hidden" class="facebook_reach_is_compare"><label class="sw"><input type="checkbox"  class="facebook_reach_compare" '.$attrchecked.' ><div class="sw-pan"></div><div class="sw-btn"></div></label><label>Compare to:</label><select class="form-control" id="facebook_reach_comparison" '.$attr.'><option class="facebook_reach_previous_period" selected="selected" value="previous_period" '.$previousPeriodAttr.'>Previous period</option><option class="facebook_reach_previous_year" value="previous_year" '.$previousYearAttr.'>Previous year</option></select></div><div class="form-group uk-flex" id="facebook_reach_previous-section"><div id="facebook_reach_previous_range" class="form-control rangepicker facebook_daterangepicker"><input type="hidden" class="facebook_reach_prev_start_date" value="'.$previousStartDate.'"><input type="hidden" class="facebook_reach_prev_end_date" value="'.$previousEndDate.'"><input type="hidden" class="facebook_reach_prev_comparison_days"><i class="fa fa-calendar"></i><p></p></div></div><div class="uk-flex"><input type="button" class="btn blue-btn facebook_reach_apply_btn" value="Apply" ><a href="javascript:;" class="facebook_reach_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a></div></div></form></div>';
            }

        }else{
            $html='<div class="filter-list fbpagefilter"><ul><li><p class="facebook_time"></p></li><li><a href="javascript:;" id="facebook_dateRange" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center"><img src="'.url('public/vendor/internal-pages/images/date-rance-calender-icon.png').'"></a></li><li><a href="javascript:;" data-request-id="{{$campaign_id}}" id="refreshFacebookData" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Facebook Page Data; pos: top-center" title="" aria-expanded="false"><img src="'.url('public/vendor/internal-pages/images/refresh-icon.png').'"></a></li></ul></div><div class="dateRange-popup" id="facebook-dateRange-popup"><form><div class="dateRange-fields"><div class="form-group uk-flex"><label>Date Range</label></div><div class="form-group uk-flex"><div id="facebook_current_range" class="form-control rangepicker facebook_daterangepicker"><input type="hidden" class="facebook_start_date" value="'.$facebook['start_date'].'"><input type="hidden" class="facebook_end_date" value="'.$facebook['end_date'].'"><input type="hidden" class="facebook_current_label" value="'.$facebook['duration'].'"><input type="hidden" class="facebook_comparison_days"><i class="fa fa-calendar"></i><p></p></div></div><div class="form-group uk-flex"><input type="hidden" class="facebook_is_compare"><label class="sw"><input type="checkbox"  class="facebook_compare" '.$attrchecked.' ><div class="sw-pan"></div><div class="sw-btn"></div></label><label>Compare to:</label><select class="form-control" id="facebook_comparison" '.$attr.'><option class="facebook_previous_period" selected="selected" value="previous_period" '.$previousPeriodAttr.'>Previous period</option><option class="facebook_previous_year" value="previous_year" '.$previousYearAttr.'>Previous year</option></select></div><div class="form-group uk-flex" id="facebook-previous-section"><div id="facebook_previous_range" class="form-control rangepicker facebook_daterangepicker"><input type="hidden" class="facebook_prev_start_date" value="'.$previousStartDate.'"><input type="hidden" class="facebook_prev_end_date" value="'.$previousEndDate.'"><input type="hidden" class="facebook_prev_comparison_days"><i class="fa fa-calendar"></i><p></p></div></div><div class="uk-flex"><input type="button" class="btn blue-btn facebook_apply_btn" value="Apply" ><a href="javascript:;" class="facebook_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a></div></div></form></div>';
        }

        return response()->json($html);
    }

    public function campaign_fb_content($campaign_id){
        $dashboardStatus = CampaignDetailController::dashboardStatus('Social',$campaign_id);
        $gtUser = SemrushUserAccount::with('ProfileInfo')->where('id',$campaign_id)->first();
        $profile_data = $gtUser;
      
        return \View::make('viewkey.dashboards.social',compact('campaign_id','dashboardStatus','gtUser','profile_data'));
    }

    public function getFbViewLikes($campaign_id){
        return view('viewkey.dashboards.facebook_likes',compact('campaign_id'));
    }

    public function getFbViewReach($campaign_id){
        return view('viewkey.dashboards.facebook_reach',compact('campaign_id'));
    }

    public function getFbViewPosts($campaign_id){
        return view('viewkey.dashboards.facebook_post_reviews',compact('campaign_id'));
    }

    public function getFbViewReviews($campaign_id){
        return view('viewkey.dashboards.facebook_reviews',compact('campaign_id'));
    }

    public function getFbView($campaign_id){
        return view('vendor.social.facebook-sections.facebook_view',compact('campaign_id'));
    }

}