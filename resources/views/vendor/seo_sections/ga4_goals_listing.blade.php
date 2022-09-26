 @if($comparison == 1)
<tr class="total-comparison">
<th>Totals</th>
<th>
<span>{{number_format($total_active_users)}}</span>
<span>vs {{number_format($prev_total_active_users)}}</span>
<p class="<?php if($response_data['total']['active_users'] > 0){ echo 'green';}elseif($response_data['total']['active_users'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['active_users'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['active_users'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?> 
  <span>{{ str_replace('-','',$response_data['total']['active_users']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_sessions)}}</span>
<span>vs {{number_format($prev_total_sessions)}}</span>
<p class="<?php if($response_data['total']['sessions'] > 0){ echo 'green';}elseif($response_data['total']['sessions'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['sessions'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['sessions'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?> 
  <span>{{ str_replace('-','',$response_data['total']['sessions']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_engaged_sessions)}}</span>
<span>vs {{number_format($prev_total_engaged_sessions)}}</span>
<p class="<?php if($response_data['total']['engaged_sessions'] > 0){ echo 'green';}elseif($response_data['total']['engaged_sessions'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['engaged_sessions'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['engaged_sessions'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?>
  <span>{{ str_replace('-','',$response_data['total']['engaged_sessions']) .'%'}}</span>
</p>
</th>
<th>
<span>{{ $total_userEngagementDuration }}</span>
<span>vs {{ $prev_total_userEngagementDuration}}</span>
 <p class="<?php if($response_data['total']['average_engagement_time_per_session'] > 0){ echo 'green';}elseif($response_data['total']['average_engagement_time_per_session'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['average_engagement_time_per_session'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['average_engagement_time_per_session'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?> 
  <span>{{ str_replace('-','',$response_data['total']['average_engagement_time_per_session']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_engaged_sessions_perSession,2)}}</span>
<span>vs {{number_format($prev_total_engaged_sessions_perSession,2)}}</span>
<p class="<?php if($response_data['total']['engaged_sessions_perSession'] > 0){ echo 'green';}elseif($response_data['total']['engaged_sessions_perSession'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['engaged_sessions_perSession'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['engaged_sessions_perSession'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?>
  <span>{{ str_replace('-','',$response_data['total']['engaged_sessions_perSession']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_eventsPerSession,2)}}</span>
<span>vs {{number_format($prev_total_eventsPerSession,2)}}</span>
<p class="<?php if($response_data['total']['eventsPerSession'] > 0){ echo 'green';}elseif($response_data['total']['eventsPerSession'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['eventsPerSession'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['eventsPerSession'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?>
  <span>{{ str_replace('-','',$response_data['total']['eventsPerSession']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_engagementRate,2)}}</span>
<span>vs {{number_format($prev_total_engagementRate,2)}}</span>
<p class="<?php if($response_data['total']['engagement_rate'] > 0){ echo 'green';}elseif($response_data['total']['engagement_rate'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['engagement_rate'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['engagement_rate'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?> 
  <span>{{ str_replace('-','',$response_data['total']['engagement_rate']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_eventCount)}}</span>
<span>vs {{number_format($prev_total_eventCount)}}</span>
<p class="<?php if($response_data['total']['event_count'] > 0){ echo 'green';}elseif($response_data['total']['event_count'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['event_count'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['event_count'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?> 
  <span>{{ str_replace('-','',$response_data['total']['event_count']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_conversions)}}</span>
<span>vs {{number_format($prev_total_conversions)}}</span>
<p class="<?php if($response_data['total']['conversions'] > 0){ echo 'green';}elseif($response_data['total']['conversions'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['conversions'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['conversions'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?>  
  <span>{{ str_replace('-','',$response_data['total']['conversions']) .'%'}}</span>
</p>
</th>
<th>
<span>{{number_format($total_totalRevenue)}}</span>
<span>vs {{number_format($prev_total_totalRevenue)}}</span>
<p class="<?php if($response_data['total']['total_revenue'] > 0){ echo 'green';}elseif($response_data['total']['total_revenue'] < 0){ echo 'red';}?>">
<?php if($response_data['total']['total_revenue'] > 0){ echo '<i class="icon ion-arrow-up-a"></i>';}elseif($response_data['total']['total_revenue'] < 0){ echo '<i class="icon ion-arrow-down-a"></i>';}?>  
  <span>{{ str_replace('-','',$response_data['total']['total_revenue']) .'%'}}</span>
</p>
</th>
</tr>
@endif
@if($status == 1)
<?php  $i =1; ?>
@foreach($response_data['current'] as $key=>$value)
  @if($comparison == 1)
      <tr><td colspan="11">{{ $i.'. '.$value['channel_group'] }}</td></tr>
  @endif
  <tr>
      @if($comparison == 0)
          <td>{{ $i.'. '.$value['channel_group'] }}</td>
      @else
          <td>{{ $display_range }}</td>
      @endif
      <td>{{ number_format($value['active_users']) }}</td>
      <td>{{ number_format($value['sessions']) }}</td>
      <td>{{ number_format($value['engaged_sessions']) }}</td>
      <td>{{ $value['average_engagement_time_per_session'] }}</td>
      <td>{{ number_format($value['engaged_sessions_per_user'],2) }}</td>
      <td>{{ number_format($value['events_per_session'],2) }}</td>
      <td>{{ number_format($value['engagement_rate'],2).'%' }}</td>
      <td>{{ number_format($value['event_count']) }}</td>
      <td>{{ number_format($value['conversions']) }}</td>
      <td>{{ number_format($value['total_revenue']) }}</td>
  </tr>

  @if($comparison == 1)
  <?php 
  $engaged_sessions_per_user = App\GoogleAnalyticAccount::calculate_percentage($value['engaged_sessions_per_user'],$response_data['previous'][$key]['engaged_sessions_per_user']);
  $events_per_session = App\GoogleAnalyticAccount::calculate_percentage($value['events_per_session'],$response_data['previous'][$key]['events_per_session']);
  ?>
      <tr>
          <td>{{ $prev_display_range}}</td>
          <td>{{ number_format($response_data['previous'][$key]['active_users'])}}</td>
          <td>{{ number_format($response_data['previous'][$key]['sessions'])}}</td>
          <td>{{ number_format($response_data['previous'][$key]['engaged_sessions'])}}</td>
          <td>{{ $response_data['previous'][$key]['average_engagement_time_per_session']}}</td>
          <td>{{ number_format($response_data['previous'][$key]['engaged_sessions_per_user'],2)}}</td>
          <td>{{ number_format($response_data['previous'][$key]['events_per_session'],2)}}</td>
          <td>{{ number_format($response_data['previous'][$key]['engagement_rate'],2).'%'}}</td>
          <td>{{ number_format($response_data['previous'][$key]['event_count'])}}</td>
          <td>{{ number_format($response_data['previous'][$key]['conversions'])}}</td>
          <td>{{ number_format($response_data['previous'][$key]['total_revenue'])}}</td>
      </tr>

      <tr>
          <td>% change</td>
          <td>{{($response_data['percentage'][$key]['active_users'] == 0) ? '-' : $response_data['percentage'][$key]['active_users'].'%'}}</td>
          <td>{{($response_data['percentage'][$key]['sessions'] == 0) ? '-' : $response_data['percentage'][$key]['sessions'].'%'}}</td>
          <td>{{($response_data['percentage'][$key]['engaged_sessions'] == 0) ? '-' : $response_data['percentage'][$key]['engaged_sessions'].'%'}}</td>
          <td>{{($response_data['percentage'][$key]['average_engagement_time_per_session'] == 0) ? '-' : $response_data['percentage'][$key]['average_engagement_time_per_session'].'%'}}</td>
          <td>{{ ($engaged_sessions_per_user == 0)? '-' : $engaged_sessions_per_user .'%' }}</td>
          <td>{{ ($events_per_session == 0)? '-' : $events_per_session .'%' }}</td>
          <td>{{($response_data['percentage'][$key]['engagement_rate'] == 0) ? '-' : $response_data['percentage'][$key]['engagement_rate'].'%'}}</td>
          <td>{{($response_data['percentage'][$key]['event_count'] == 0) ? '-' : $response_data['percentage'][$key]['event_count'].'%'}}</td>
          <td>{{($response_data['percentage'][$key]['conversions'] == 0) ? '-' : $response_data['percentage'][$key]['conversions'].'%'}}</td>
          <td>{{($response_data['percentage'][$key]['total_revenue'] == 0) ? '-' : $response_data['percentage'][$key]['total_revenue'].'%'}}</td>            
      </tr>    
  @endif
  <?php  $i++; ?>
@endforeach
@else
<tr><td colspan="11">No data available</td></tr>
@endif