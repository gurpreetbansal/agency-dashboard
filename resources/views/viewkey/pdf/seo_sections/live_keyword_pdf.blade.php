<!--Live Keyword Tracking Row -->
<style>
.no-page-break {
   page-break-before: unset !important;
   page-break-after: unset !important;
   page-break-inside: unset !important;
}
.project-detail-body {
   margin-bottom: 0;
}
</style>
<div class="white-box pa-0 mb-40 no-page-break" >        
   <!-- <div class="section-head">
       <h4>
            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/live-keyword-tracking-img.png')}}"></figure>
           Position Tracking: Top Ranking Changes (<span class="active_keywords_count">0 active keywords</span>)
           <font class="keyword_time"></font>
       </h4>
    </div> -->
    <div class="box-boxshadow">
      <div class="section-head">
        <h4>
         <figure><img src="{{URL::asset('public/vendor/internal-pages/images/live-keyword-tracking-img.png')}}"></figure> 
         Position Tracking: Top Ranking Changes (<span class="active_keywords_count"><?php if(isset($live_stats) && $live_stats['total'] > 0){echo $live_stats['total'];}else{echo '0';}?> active keywords</span>)
         <font class="keyword_time"></font>
      </h4>
      <hr />
      <p>
         This report lists all keywords in the tracking campaign, the position of the domain(s) for these keywords in the Google top 100 and position changes in 1 day, 7 days, 30 days and lifetime.
      </p>
      <ul>
       <li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Keyword:</b> A Search term from the current tracking campaign</li>
       @if(!in_array('start_rank',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Start:</b> Position of the keywords on day 1 of the campaign</li> @endif
       @if(!in_array('page',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Google Page:</b> Page number of the keyword in Google's SERP results</li> @endif
       @if(!in_array('google_rank',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Google Rank:</b> Current position of the keyword</li> @endif
       @if(!in_array('oneday',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">1 Day Change:</b> Position change of the keyword in the last 24 hours</li> @endif
       @if(!in_array('weekly',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">7 Day Change:</b> Position change of the keyword in last the 7 days</li> @endif
       @if(!in_array('monthly',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">30 Day Change:</b> Position change of the keyword in last the 30 days</li> @endif
       @if(!in_array('lifetime',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">LifeTime Change:</b> Position change of the keyword since day 1 of the campaign</li> @endif
       @if(!in_array('competition',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Competition:</b> Total number of webpages ranking for the keyword in millions</li> @endif
       @if(!in_array('sv',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">Search Volume:</b> Estimated monthly searches for the keyword</li> @endif
       @if(!in_array('url',$table_settings))<li><b class="uk-text-medium"><img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="">URL:</b> Current ranking URL for the keyword</li> @endif
    </ul>
   </div>

    <div class="white-box-body">
      <div uk-grid class="mb-40 mt-space">
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" keywords_up">{{ @$live_stats['lifetime'] }}</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/keywords-up-img.png')}}"> Keywords Up</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-all-since">since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-three <?php if($live_stats['since_three'] > 0){ echo 'green';}elseif($live_stats['since_three'] < 0 ){ echo 'red';}?>" >
                     {{ $live_stats['three'] }}
                     <small>/{{ $live_stats['total'] }}</small>
                  </h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 3</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-three-since">@if($live_stats['since_three'] > 0)<i class="icon ion-arrow-up-a"></i>@elseif($live_stats['since_three'] < 0 )<i class="icon ion-arrow-up-a"></i>@endif<strong>{{ $live_stats['since_three'] }}</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-ten <?php if($live_stats['since_ten'] > 0){ echo 'green';}elseif($live_stats['since_ten'] < 0 ){ echo 'red';}?>">
                     {{ $live_stats['ten'] }}
                     <small>/{{ $live_stats['total'] }}</small>
                  </h6>

                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 10</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-ten-since">@if($live_stats['since_ten'] > 0)<i class="icon ion-arrow-up-a"></i>@elseif($live_stats['since_ten'] < 0 )<i class="icon ion-arrow-up-a"></i>@endif<strong>{{ $live_stats['since_ten'] }}</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-twenty <?php if($live_stats['since_twenty'] > 0){ echo 'green';}elseif($live_stats['since_twenty'] < 0 ){ echo 'red';}?>">
                     {{ $live_stats['twenty'] }}
                     <small>/{{ $live_stats['total'] }}</small>
                  </h6>

                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 20</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-twenty-since">@if($live_stats['since_twenty'] > 0)<i class="icon ion-arrow-up-a"></i>@elseif($live_stats['since_twenty'] < 0 )<i class="icon ion-arrow-up-a"></i>@endif<strong>{{ $live_stats['since_twenty'] }}</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-thirty <?php if($live_stats['since_thirty'] > 0){ echo 'green';}elseif($live_stats['since_thirty'] < 0 ){ echo 'red';}?>">
                     {{ $live_stats['thirty'] }}
                     <small>/{{ $live_stats['total'] }}</small>
                  </h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 30</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-thirty-since">@if($live_stats['since_thirty'] > 0)<i class="icon ion-arrow-up-a"></i>@elseif($live_stats['since_thirty'] < 0 )<i class="icon ion-arrow-up-a"></i>@endif<strong>{{ $live_stats['since_thirty'] }}</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class="top-hundred <?php if($live_stats['since_hundred'] > 0){ echo 'green';}elseif($live_stats['since_hundred'] < 0 ){ echo 'red';}?>">
                     {{ $live_stats['hundred'] }}
                     <small>/{{ $live_stats['total'] }}</small>
                  </h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 100</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-hundred-since">@if($live_stats['since_hundred'] > 0)<i class="icon ion-arrow-up-a"></i>@elseif($live_stats['since_hundred'] < 0 )<i class="icon ion-arrow-up-a"></i>@endif<strong>{{ $live_stats['since_hundred'] }}</strong> since start</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<div class="project-table-cover BreakBefore">
  <div class="box-boxshadow">
   <div class="table-box project-table-body LiveKeywordTable" id="LiveKeywordTableDiv">
      <table id="LiveKeywordTable_data">
         <thead>
            <tr>
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="keyword">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  Keyword
               </th>
               @if(!in_array('start_rank',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="start_ranking">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  Start
               </th>
               @endif
               @if(!in_array('page',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="currentPosition">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  <img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}">
                  Page
               </th>
               @endif
               @if(!in_array('google_rank',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="currentPosition">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  <img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}">
                  Rank
               </th>
               @endif
               @if(!in_array('oneday',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="oneday_position">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  1 Day
               </th>
               @endif
               @if(!in_array('weekly',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="one_week_ranking">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  7 Days
               </th>
               @endif
               @if(!in_array('monthly',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="monthly_ranking">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  30 Days
               </th>
               @endif
               @if(!in_array('lifetime',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="life_ranking">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  Life
               </th>
               @endif
               @if(!in_array('competition',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="cmp">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  Comp
               </th>
               @endif
               @if(!in_array('sv',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="sv">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  S Vo
               </th>
               @endif
               @if(!in_array('date',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="created_at">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  Date Added
               </th>
               @endif
               @if(!in_array('url',$table_settings))
               <th class="liveKeyword_sorting" data-sorting_type="asc" data-column_name="result_url">
                  <span uk-icon="arrow-up"></span>
                  <span uk-icon="arrow-down"></span>
                  URL
               </th>
               @endif
            </tr>
         </thead>
         <tbody>

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
               $pages = '>10';
            }
         }else{
            $pages = '>10';
         }

         $img = $value->get_flag_data($value->region);

         ?>

         <tr class="<?php if($key%2 == 0){ echo 'even';}else{ echo 'odd';} if($value->is_favorite == 1){ echo ' active';}?>">
            <td>
               <div class="flex">
                  <i class="fa fa-star"></i>
                  @if(!empty($img))
                  <figure class="keyword-flag-icon" uk-tooltip="title: {{$value->region}}; pos: top-left">
                     <img src="{{$img}}">
                  </figure>
                  @endif
                  <h6 uk-tooltip="title: {{$value->keyword}}; pos: top-left"><a href="javascript:;">{{$value->keyword}}</a></h6>
               </div>
               </td>
                  @if(!in_array('start_rank',$table_settings))
                  <td class="<?php if($value->startPosition >= 100){ echo 'grey';}?>" data-id="{{$value->id}}" data-value="<?php if($value->startPosition > 100){ echo '>100';}else{ echo $value->startPosition; } ?>" >
                     @if($value->startPosition != null && $value->startPosition < 100)
                     {{$value->startPosition}}
                     @else
                     {{'>100'}}
                     @endif
                  </td>
                  @endif
                  @if(!in_array('page',$table_settings))
                  <td class="<?php if($pages > 10 || $pages == '>10'){ echo 'grey';}?>">{{$pages}}</td>
                  @endif
                  @if(!in_array('google_rank',$table_settings))
                  <td class="<?php if($value->currentPosition >= 100){ echo 'grey';}?>">
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
                  @endif
                  @if(!in_array('oneday',$table_settings))
                  <td>
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
                  @endif
                  @if(!in_array('weekly',$table_settings))
                  <td>
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
                  @endif
                  @if(!in_array('monthly',$table_settings))
                  <td>
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
                  @endif
                  @if(!in_array('lifetime',$table_settings))
                  <td>
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
                 @endif
                 @if(!in_array('competition',$table_settings))<td>{{number_format((float)$value->cmp, 2, '.', '')}}</td>@endif
                 @if(!in_array('sv',$table_settings))<td>{{$value->sv}}</td>@endif
                 @if(!in_array('date',$table_settings))<td>{{date('d-M-Y', strtotime($value->created_at))}}</td>@endif
                 @if(!in_array('url',$table_settings))<td><a href="{{$value->result_url}}" target="_blank">{{parse_url($value->result_url,PHP_URL_PATH)}}</a></td>@endif

              </tr>
              @if($key===250) @break;@endif
              @endforeach

              @else
              <tr><td>No Records found</td></tr>
              @endif
           </tbody>
        </table>
     </div>
  </div>

  <input type="hidden" name="hidden_page_liveKeyword" id="hidden_page_liveKeyword" value="1" />
  <input type="hidden" name="hidden_column_name_liveKeyword" id="hidden_column_name_liveKeyword" value="currentPosition" />
  <input type="hidden" name="hidden_sort_type_liveKeyword" id="hidden_sort_type_liveKeyword" value="asc" />
  <input type="hidden" name="limit_liveKeyword" id="limit_liveKeyword" value="200" />

</div>

@if(!in_array('graph',$table_settings))
<div class="keyword_graph_section page-break noBreakBefore">
   @if(isset($live_keywords) && !empty($live_keywords) && count($live_keywords) > 0)
   <?php $counter=0;?>
   @foreach($live_keywords as $l_key=>$l_value)
   <?php $counter++;?>
   <div class="pdf-graph-section {{($counter%3===0 && $l_key!==50)?'page-break noBreakAfter':''}}">
      <div class="box-boxshadow">
       <div class="section-head">
        <h4 class="campaign_name">{{$l_value->keyword.' ('.$l_value->region.')'}}</h4>
     </div>
     <div class="graph_keyword">
      <?php 
      $row_id = $l_key+1;
      $data = App\KeywordSearch::live_keyword_chart_data($l_value->request_id,$l_value->id,'-90 days');
      ?>

      <canvas id="pdf_graph{{$row_id}}" height="220"></canvas>
      <script>
         var color = Chart.helpers.color;
         
         var KeywordconfigChart<?php echo $row_id;?> = {
          type: 'line',
          data: {
           datasets: [
           {
            label: '',
            yAxisID: 'lineId',
            backgroundColor: color(window.chartColors.orange).alpha(1.5).rgbString(),
            borderColor: window.chartColors.orange,
            data:[],
            pointRadius: 4,
            fill: false,
            lineTension: 0,
            borderWidth: 1
         }
         ]
      },
      options: {
        maintainAspectRatio:false,
        scales: {
         xAxes: [{
          type: 'time',
          distribution: 'series',
          offset: true,
          ticks: {
           major: {
            enabled: true,
         },
         source: 'data',
         autoSkip: true,
         autoSkipPadding: 30,
         maxRotation: 0,
         sampleSize: 30
      }

   }],
   yAxes: [
   {
    id: 'lineId',
    gridLines: {
     drawBorder: false
  },
  scaleLabel: {
     display: true,
     labelString: 'Rank'
  },
  ticks: {
     beginAtZero: false,
     reverse:true,
     suggestedMin: 0,
          // suggestedMax: 100,
          maxTicksLimit:4
       },
       position:'left'
    }
    ]
 },
 tooltips: {
   intersect: false,
   mode: 'index',
   callbacks: {
    label: function(tooltipItem, myData) {
     var label = myData.datasets[tooltipItem.datasetIndex].label || '';
     if (label) {
      label += ': ';
   }
   label += parseFloat(tooltipItem.value).toFixed(2);
   return label;
},
labelTextColor: function(context) {
  return '#000';
}
},
backgroundColor:'rgb(255, 255, 255)',
titleFontColor:'#000'

},
legend:{
   display:false
}
}
};
if (window.myLineKeywordChart<?php echo $row_id;?>) {
   window.myLineKeywordChart<?php echo $row_id;?>.destroy();
}

var ctxs = document.getElementById('pdf_graph<?php echo $row_id;?>').getContext('2d');
window.myLineKeywordChart<?php echo $row_id;?> = new Chart(ctxs, KeywordconfigChart<?php echo $row_id;?>);

KeywordconfigChart<?php echo $row_id;?>.data.datasets[0].data = <?php echo json_encode($data["keyword"]);?>;
window.myLineKeywordChart<?php echo $row_id;?>.update();
</script>

</div>
</div>
</div>
@if($l_key===50) @break;@endif
@endforeach
@endif
</div>
@endif
</div>

</div>