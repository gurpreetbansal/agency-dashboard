 <!-- 1 Data row -->
 <?php 
 if(!empty($checked_id) && isset($checked_id)){
  $keyId = explode(',',$checked_id);
 }
?>
@if(isset($live_keywords) && !empty($live_keywords) && count($live_keywords) > 0)
@foreach($live_keywords as $key=>$value)
<tr class="<?php if($key%2 == 0){ echo 'odd';}else{ echo 'even';} if($value->is_favorite == 1){ echo ' active';}?>">
 <td class="ajax-loader">
  <div class="flex">
   <i class="fa fa-star"></i>
   @if(!empty($value->regional_flag))
   <figure class="keyword-flag-icon" uk-tooltip="title: {{$value->region}}; pos: top-left">
     <img src="{{$value->regional_flag}}">
   </figure>
   @endif
   <figure class="location-icon" uk-tooltip="title: {{$value->canonical}}; pos: top-left">
    <img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}">
  </figure>
  <h6 uk-tooltip="title: {{$value->keyword}}; pos: top-left">{{$value->keyword}}
  </h6>
  <div class="icons-list fixed">
    @if($state == 'user')  
    @if(Auth::user()->role_id != 4)
    @if($value->is_favorite == 1)
    <a href="javascript:;" class="mark_favorite" uk-tooltip="title:Unfavourite this keyword; pos: top-center" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}">
      <i class="fa fa-star"></i>
    </a>
    @else
    <a href="javascript:;" class="mark_favorite" uk-tooltip="title:Favourite this keyword; pos: top-center" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}">
      <i class="fa fa-star-o"></i>
    </a>
    @endif
    @endif
    @endif
    @if(!in_array('graph',$table_settings))
    <a href="javascript:;" class="downArrow chart-icon" uk-tooltip="title:Show Historical Chart; pos: top-center"
    data-toggle="collapse" data-target=".chartRow{{$key+1}}" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}"><i class="fa fa-area-chart"></i></a>
    @endif
    <a href="{{ config('app.base_url').'spyglass/'.base64_encode($value->id.'-|-'.$value->user_id.'-|-'.time()) }}" uk-tooltip="title:See the keyword in search results; pos: top-center" target="_blank">
     <i class="fa fa-search"></i>
   </a>
 </div>
</div>
</td>
@if(!in_array('start_rank',$table_settings))
<td class="@if($state == 'user')editPosition @endif <?php if($value->startPosition >= 100){ echo 'grey';}?> ajax-loader" data-id="{{$value->id}}" data-value="<?php if($value->startPosition > 100){ echo '>100';}else{ echo $value->startPosition; } ?>" >
  @if($value->is_sync == 1)
  @if($value->startPosition != null && $value->startPosition < 100)
  {{$value->startPosition}}
  @else
  {{'>100'}}
  @endif
  @else
  {{'??'}}
  @endif

</td>
@endif
@if(!in_array('page',$table_settings))
<td class="<?php if($value->c_position > 10 || $value->c_position == '>10'){ echo 'grey';}?> ajax-loader">
 @if($value->is_sync == 1)
 {{$value->c_position}}
 @else
 {{'??'}}
 @endif
</td>
@endif
@if(!in_array('google_rank',$table_settings))
<td class="<?php if($value->currentPosition >= 100){ echo 'grey';}?> ajax-loader">
 @if($value->position_type == '1')
 <i class="icon ion-flag" uk-tooltip="title:Local Pack; pos: top-center"></i>
 @elseif($value->position_type == '2')
 <i class="icon ion-android-bookmark" uk-tooltip="title:Featured Snippet; pos: top-center"></i>
 @endif
 @if($value->is_sync == 1)
 @if($value->currentPosition != null && $value->currentPosition < 100)
 {{$value->currentPosition}}
 @else
 {{'>100'}}
 @endif
 @else
 {{'??'}}
 @endif
</td>
@endif
@if(!in_array('oneday',$table_settings))
<td class="ajax-loader">
 @if($value->is_sync == 1)
 @if($value->oneDayPostion != null && $value->oneDayPostion > 0)
 <i class="icon ion-arrow-up-a"></i>
 {{$value->oneDayPostion}}
 @elseif($value->oneDayPostion != null && $value->oneDayPostion < 0)
 <i class="icon ion-arrow-down-a"></i>
 {{trim($value->oneDayPostion,'-')}}
 @else
 {{'-'}}
 @endif
 @else
 {{'??'}}
 @endif
</td>
@endif
@if(!in_array('weekly',$table_settings))
<td class="ajax-loader">
 @if($value->is_sync == 1)
 @if($value->weekPostion != null && $value->weekPostion > 0)
 <i class="icon ion-arrow-up-a"></i>
 {{$value->weekPostion}}
 @elseif($value->weekPostion != null && $value->weekPostion < 0)
 <i class="icon ion-arrow-down-a"></i>
 {{trim($value->weekPostion,'-')}}
 @else
 {{'-'}}
 @endif
 @else
 {{'??'}}
 @endif
</td>
@endif
@if(!in_array('monthly',$table_settings))
<td class="ajax-loader">
 @if($value->is_sync == 1)
 @if($value->monthPostion != null && $value->monthPostion > 0)
 <i class="icon ion-arrow-up-a"></i>
 {{$value->monthPostion}}
 @elseif($value->monthPostion != null && $value->monthPostion < 0)
 <i class="icon ion-arrow-down-a"></i>
 {{trim($value->monthPostion,'-')}}
 @else
 {{'-'}}
 @endif 
 @else
 {{'??'}}
 @endif
</td>
@endif
@if(!in_array('lifetime',$table_settings))
<td class="ajax-loader">
 @if($value->is_sync == 1)
 @if($value->lifeTime != null && $value->lifeTime > 0)
 <i class="icon ion-arrow-up-a"></i>
 {{$value->lifeTime}}
 @elseif($value->lifeTime != null && $value->lifeTime <= 0)
 <i class="icon ion-arrow-down-a"></i>
 {{trim($value->lifeTime,'-')}}
 @else
 {{'-'}}
 @endif 
 @else
 {{'??'}}
 @endif
</td>
@endif
@if(!in_array('competition',$table_settings))<td class="ajax-loader">{{number_format((float)$value->cmp, 2, '.', '')}}</td>@endif
@if(!in_array('sv',$table_settings))<td class="ajax-loader">{{$value->sv}}</td>@endif
@if(!in_array('date',$table_settings))<td class="ajax-loader">{{date('d-M-Y', strtotime($value->created_at))}}</td>@endif
@if(!in_array('url',$table_settings))<td class="ajax-loader"><a href="{{$value->result_url}}" target="_blank">
@if($value->url_type==2)
  <span uk-icon="bolt" uk-tooltip="title:Exact URL Tracking; pos: top-center"></span>
@endif
  {{parse_url($value->result_url,PHP_URL_PATH)}}</a></td>@endif

@if($state == 'user')
@if(Auth::user()->role_id !=4)
<td class="ajax-loader">
 <input class="uk-checkbox selected_keywords" type="checkbox" name="selected_keywords[]" value="{{$value->id}}" <?php if(isset($keyId) && !empty($keyId)){ if(in_array($value->id,$keyId)){echo "checked";} }?>>  
</td>
@endif
@endif
</tr>
<!-- <tr class="collapse chartRow1"> -->
  @if(!in_array('graph',$table_settings))
  <tr class="collapse chartRow{{$key+1}}">
   <td colspan="13">
    <div class="chart">
     <div class="filter-list live-keyword-section">
       <ul>
         <li>
           <button type="button" class="liveKeywordBtns active" data-value="-60 day" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}">60 days</button>
         </li>
         <li>
           <button type="button" class="liveKeywordBtns " data-value="-90 day" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}">90 days</button>
         </li>
         <li>
           <button type="button" class="liveKeywordBtns " data-value="-180 day" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}">180 days</button>
         </li>
         <li>
           <button type="button" class="liveKeywordBtns " data-value="-365 day" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}">365 days</button>
         </li>
         <li>
           <button type="button" class="liveKeywordBtns " data-value="all" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}">All Time</button>
         </li>
       </ul>
     </div>
     <canvas id="livekeywordchart{{$key+1}}" height="300"></canvas>
   </div>
 </td>
</tr>
@endif

@endforeach

@else
<tr class="no_keyword"><td colspan="13"><center>No keyword found</center></td></tr>
@endif
                  <!-- 1 Data row -->