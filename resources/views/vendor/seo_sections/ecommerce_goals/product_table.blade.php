<?php 

//current data
$arr_name = $keysArr['keysArr']['arr_name'];
$location = $keysArr['keysArr']['product'];
$goal_value = $keysArr['keysArr']['quantity'];
$arr_name_organic = $keysArr['keysArr']['arr_name_organic'];
$location_organic = $keysArr['keysArr']['product_organic'];
$goal_value_organic = $keysArr['keysArr']['quantity_organic'];

//previous data
$prev_arr_name = $keysArr['keysArr']['prev_arr_name'];
$prev_location = $keysArr['keysArr']['prev_product'];
$prev_goal_value = $keysArr['keysArr']['prev_quantity'];
$prev_arr_name_organic = $keysArr['keysArr']['prev_arr_name_organic'];
$prev_location_organic = $keysArr['keysArr']['prev_product_organic'];
$prev_goal_value_organic = $keysArr['keysArr']['prev_quantity_organic'];


if(count($final->$arr_name->$location) > 0){
  foreach($results as $key=>$value){
    $organic_key = array_search($value,$final->$arr_name_organic->$location_organic);
    $users_prev_key = array_search($value,$final->$prev_arr_name->$prev_location);
    $organic_prev_key = array_search($value,$final->$prev_arr_name_organic->$prev_location_organic);


//percentage values
    if($stats_data['final_current_conversionRate'] > 0){
      if($key !== false){
        $current_users_percentage = number_format(($final->$arr_name->$goal_value[$key]/$stats_data['final_current_conversionRate'])*100,2);
      }else{
        $current_users_percentage = '100';
      }
    }else{
      $current_users_percentage = '0.00';
    }
    
    if($stats_data['final_current_conversionRate_organic'] > 0){
      if($organic_key !== false){
        $organic_percentage = number_format(($final->$arr_name_organic->$goal_value_organic[$organic_key]/$stats_data['final_current_conversionRate_organic'])*100,2);
      }else{
       $organic_percentage = '100';
     }
   }else{
    $organic_percentage = '0.00';
  }
  if($stats_data['final_previous_conversionRate'] > 0){
   if($users_prev_key !== false){
    $prev_users_percentage = number_format(($final->$prev_arr_name->$prev_goal_value[$users_prev_key]/$stats_data['final_previous_conversionRate'])*100,2);
  }else{
    $prev_users_percentage = '100';
  }
}else{
  $prev_users_percentage = '0.00';
}
if($stats_data['final_current_conversionRate'] > 0){
  if($organic_prev_key !== false){
    $prev_organic_percentage = number_format(($final->$prev_arr_name_organic->$prev_goal_value_organic[$organic_prev_key]/$stats_data['final_current_conversionRate'])*100,2);
  }else{
   $prev_organic_percentage = '100';
 }
}else{
  $prev_organic_percentage = '0.00';
}

?>
<tr>
  <td colspan="3">
    <h6>{{@$key+1}} . <a href="javascript:;" uk-tooltip="{{@$value}}" class="text-primary">{{@$value}}</a></h6>
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
   @if($key !== false)
   ${{number_format($final->$arr_name->$goal_value[$key],2)}}
   @else
   $0.00
   @endif
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
    ${{number_format($final->$arr_name_organic->$goal_value_organic[$organic_key],2)}}
    @else
    $0.00
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
    ${{number_format($final->$prev_arr_name->$prev_goal_value[$users_prev_key],2)}}
    @else
    $0.00
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
    ${{number_format($final->$prev_arr_name_organic->$prev_goal_value_organic[$organic_prev_key],2)}}
    @else
    $0.00
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
  <center><h6>There is no data for this view</h6></center>
  </td>
  </tr>';

}

?>