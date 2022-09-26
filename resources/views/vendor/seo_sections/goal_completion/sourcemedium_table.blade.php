<?php 

//current data
$arr_name = $keysArr['keysArr']['arr_name'];
$location = $keysArr['keysArr']['location'];
$goal_value = $keysArr['keysArr']['goal_value'];
$arr_name_organic = $keysArr['keysArr']['arr_name_organic'];
$location_organic = $keysArr['keysArr']['location_organic'];
$goal_value_organic = $keysArr['keysArr']['goal_value_organic'];

//previous data
$prev_arr_name = $keysArr['keysArr']['prev_arr_name'];
$prev_location = $keysArr['keysArr']['prev_location'];
$prev_goal_value = $keysArr['keysArr']['prev_goal_value'];
$prev_arr_name_organic = $keysArr['keysArr']['prev_arr_name_organic'];
$prev_location_organic = $keysArr['keysArr']['prev_location_organic'];
$prev_goal_value_organic = $keysArr['keysArr']['prev_goal_value_organic'];


if(count($final->$arr_name->$location) > 0){
  foreach($results as $key=>$value){
    $organic_key = array_search($value,$final->$arr_name_organic->$location_organic);
    $users_prev_key = array_search($value,$final->$prev_arr_name->$prev_location);
    $organic_prev_key = array_search($value,$final->$prev_arr_name_organic->$prev_location_organic);
   
   // dd($organic_prev_key);

//percentage values
    if($stats_data['final_current_goal_completion'] > 0){
      if($key != false && $key >0){
        $current_users_percentage = number_format(($final->$arr_name->$goal_value[$key]/$stats_data['final_current_goal_completion'])*100,2);
      }else{
        $current_users_percentage = '100';
      }
    }else{
      $current_users_percentage = '0.00';
    }
    if($stats_data['final_current_goal_completion_organic'] > 0){
      if($organic_key !=false && $organic_key > 0){
        $organic_percentage = number_format(($final->$arr_name_organic->$goal_value_organic[$organic_key]/$stats_data['final_current_goal_completion_organic'])*100,2);
      }else{
       $organic_percentage = '100';
     }
   }else{
    $organic_percentage = '0.00';
  }
  if($stats_data['final_previous_goal_completion_organic'] > 0){
   if($users_prev_key !=false && $users_prev_key > 0){
    $prev_users_percentage = number_format(($final->$prev_arr_name->$prev_goal_value[$users_prev_key]/$stats_data['final_previous_goal_completion_organic'])*100,2);
  }else{
    $prev_users_percentage = '100';
  }
}else{
  $prev_users_percentage = '0.00';
}
if($stats_data['final_current_goal_completion'] > 0){
  if($organic_prev_key !=false && $organic_prev_key > 0){
    $prev_organic_percentage = number_format(($final->$prev_arr_name_organic->$prev_goal_value_organic[$organic_prev_key]/$stats_data['final_current_goal_completion'])*100,2);
  }else{
   $prev_organic_percentage = '100';
 }
}else{
  $prev_organic_percentage = '0.00';
}

?>
<tr>
  <td colspan="3">
    <h6>{{@$key+1}} .<a href="javascript:;">{{@$value}}</a></h6>
  </td>
</tr>
@if($compare_status == 1)
<tr>
  <td>
    <p><strong>{{@$start_date .' - '.@$end}}</strong></p>
  </td>
  <td>

  </td>
  <td>

  </td>
</tr>
@endif
<tr>
  <td>
    All Users
  </td>
  <td>
   
   {{$final->$arr_name->$goal_value[$key]}}
  
 </td>
 <td>
  <div class="flex">
    <progress id="js-progressbar" class="uk-progress" value="{{@$current_users_percentage}}" max="100"></progress> <p>{{@$current_users_percentage}}%</p>
  </div>
</td>
</tr>
<tr>
  <td>
    Organic Traffic
  </td>
  <td>
    @if($organic_key !== false)
    {{$final->$arr_name_organic->$goal_value_organic[$organic_key]}}
    @else
    0
    @endif
  </td>
  <td>
    <div class="flex">
      <progress id="js-progressbar" class="uk-progress" value="{{@$organic_percentage}}" max="100"></progress> <p>{{@$organic_percentage}}%</p>
    </div>
  </td>
</tr>
@if($compare_status == 1)
<tr>
  <td>
    <p><strong>{{@$prev_date .' - '.@$prev_day}}</strong></p>
  </td>
  <td>

  </td>
  <td>

  </td>
</tr>

<tr>
  <td>
    All Users
  </td>
  <td>
    @if($users_prev_key !== false)
    {{$final->$prev_arr_name->$prev_goal_value[$users_prev_key]}}
    @else
    0
    @endif
  </td>
  <td>
    <div class="flex">
      <progress id="js-progressbar" class="uk-progress" value="{{@$prev_users_percentage}}" max="100"></progress> <p>{{@$prev_users_percentage}}%</p>
    </div>
  </td>
</tr>
<tr>
  <td>
    Organic Traffic
  </td>
  <td>
    @if($organic_prev_key !== false)
   {{$final->$prev_arr_name_organic->$prev_goal_value_organic[$organic_prev_key]}}
   @else
   0
   @endif
 </td>
 <td>
  <div class="flex">
    <progress id="js-progressbar" class="uk-progress" value="{{@$prev_organic_percentage}}" max="100"></progress> <p>{{@$prev_organic_percentage}}%</p>
  </div>
</td>
</tr>
@endif
<?php
if($key == 3){
  break;
}
}
}else{
  echo '<tr>
  <td colspan="3">
  <h6>There is no data for this view</h6>
  </td>
  </tr>';

}

?>