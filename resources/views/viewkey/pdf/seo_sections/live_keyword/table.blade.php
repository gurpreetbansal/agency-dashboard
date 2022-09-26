 <!-- 1 Data row -->
 @if(isset($live_keywords) && !empty($live_keywords) && count($live_keywords) > 0)
 @foreach($live_keywords as $key=>$value)
 <?php
      if($value->currentPosition  < 100){
         $pageNo = $value->currentPosition/10;
         if($pageNo <= 1){
            $pages = 1;
         }elseif($pageNo <= 2){
            $pages = 2;
         }elseif($pageNo <= 3){
            $pages = 3;
         }elseif($pageNo <= 4){
            $pages = 4;
         }elseif($pageNo <= 5){
            $pages = 5;
         }elseif($pageNo <= 6){
            $pages = 6;
         }elseif($pageNo <= 7){
            $pages = 7;
         }elseif($pageNo <= 8){
            $pages = 8;
         }elseif($pageNo <= 9){
            $pages = 9;
         }elseif($pageNo <= 10){
            $pages = 10;
         }else{
            $pages = 10;
         }
      }else{
         $pages = 10;
      }

         $img = $value->get_flag_data($value->canonical);
          ?>

 <tr class="<?php if($key%2 == 0){ echo 'even';}else{ echo 'odd';} if($value->is_favorite == 1){ echo ' active';}?>">
   <td class="ajax-loader">
      <div class="flex">
         <i class="fa fa-star"></i>
         @if(!empty($img))
         <figure class="keyword-flag-icon" uk-tooltip="title: {{$value->region}}; pos: top-left">
            <img src="{{$img}}">
         </figure>
         @endif
         <figure class="location-icon" uk-tooltip="title: {{$value->canonical}}; pos: top-left">
            <img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}">
         </figure>
         <h6 uk-tooltip="title: {{$value->keyword}}; pos: top-left"><a href="javascript:;">{{$value->keyword}}</a>
      </h6>
      <div class="icons-list fixed">
         @if($role_id != 4)
         @if($value->is_favorite == 1)
         <a href="javascript:;" class="mark_favorite" uk-tooltip="title:Unfavorite this keyword; pos: top-center" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}">
            <i class="fa fa-star"></i>
         </a>
         @else
         <a href="javascript:;" class="mark_favorite" uk-tooltip="title:Favorite this keyword; pos: top-center" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}">
            <i class="fa fa-star-o"></i>
         </a>
         @endif
         @endif
         <a href="javascript:;" class="downArrow chart-icon" uk-tooltip="title:Show Historical Chart; pos: top-center"
         data-toggle="collapse" data-target=".chartRow{{$key+1}}" data-id="{{$value->id}}" data-request_id="{{$value->request_id}}"data-row_id="{{$key+1}}"><i class="fa fa-area-chart"></i></a>
      <a href="{{$value->result_se_check_url}}" uk-tooltip="title:See the keyword in search results; pos: top-center" target="_blank">
         <i class="fa fa-search"></i>
      </a>
   </div>
</div>
</td>
<td class="<?php if($value->startPosition >= 100){ echo 'grey';}?> ajax-loader" data-id="{{$value->id}}" data-value="<?php if($value->startPosition > 100){ echo '>100';}else{ echo $value->startPosition; } ?>" >
   @if($value->startPosition != null && $value->startPosition < 100)
   {{$value->startPosition}}
   @else
   {{'>100'}}
   @endif
</td>
<td class="<?php if($pages > 10){ echo 'grey';}?> ajax-loader">{{$pages}}</td>
<td class="<?php if($value->currentPosition >= 100){ echo 'grey';}?> ajax-loader">
   @if($value->get_position_type($value->request_id,$value->id) == '1')
   <i class="icon ion-flag" uk-tooltip="title:Local Pack; pos: top-center"></i>
   @elseif($value->get_position_type($value->request_id,$value->id) == '2')
   <i class="icon ion-android-bookmark" uk-tooltip="title:Featured Snippet; pos: top-center"></i>
   @endif
   @if($value->currentPosition != null && $value->currentPosition < 100)
   {{$value->currentPosition}}
   @else
   {{'>100'}}
   @endif
</td>
<td class="ajax-loader">
   @if($value->oneDayPostion != null && $value->oneDayPostion > 0)
   <i class="icon ion-arrow-up-a"></i>
   {{$value->oneDayPostion}}
   @elseif($value->oneDayPostion != null && $value->oneDayPostion < 0)
   <i class="icon ion-arrow-down-a"></i>
   {{trim($value->oneDayPostion,'-')}}
   @else
   {{'-'}}
   @endif
</td>
<td class="ajax-loader">
@if($value->weekPostion != null && $value->weekPostion > 0)
   <i class="icon ion-arrow-up-a"></i>
   {{$value->weekPostion}}
   @elseif($value->weekPostion != null && $value->weekPostion < 0)
   <i class="icon ion-arrow-down-a"></i>
   {{trim($value->weekPostion,'-')}}
   @else
   {{'-'}}
   @endif
</td>
<td class="ajax-loader">
   @if($value->monthPostion != null && $value->monthPostion > 0)
   <i class="icon ion-arrow-up-a"></i>
   {{$value->monthPostion}}
   @elseif($value->monthPostion != null && $value->monthPostion < 0)
   <i class="icon ion-arrow-down-a"></i>
   {{trim($value->monthPostion,'-')}}
   @else
   {{'-'}}
   @endif
</td>
<td class="ajax-loader">
    @if($value->lifeTime != null && $value->lifeTime > 0)
   <i class="icon ion-arrow-up-a"></i>
   {{$value->lifeTime}}
   @elseif($value->lifeTime != null && $value->lifeTime < 0)
   <i class="icon ion-arrow-down-a"></i>
   {{trim($value->lifeTime,'-')}}
   @else
   {{'-'}}
   @endif
</td>
<td class="ajax-loader">{{number_format((float)$value->cmp, 2, '.', '')}}</td>
<td class="ajax-loader">{{$value->sv}}</td>
<td class="ajax-loader">{{date('d-M-Y', strtotime($value->created_at))}}</td>
<td class="ajax-loader"><a href="{{$value->result_url}}" target="_blank">{{parse_url($value->result_url,PHP_URL_PATH)}}</a></td>

</tr>
<!-- <tr class="collapse chartRow1"> -->
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

@endforeach

@else
<tr><td>No Records found</td></tr>
@endif
                  <!-- 1 Data row -->