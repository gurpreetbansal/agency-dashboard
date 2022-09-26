<!--Live Keyword Tracking Row -->

<div class="white-box pa-0">
   <div class="box-boxshadow">
      <div class="section-head">
          <h4>
            <figure><img src="{{URL::asset('public/vendor/internal-pages/images/live-keyword-tracking-img.png')}}"></figure> 
            Position Tracking: Top Ranking Changes (<span class="active_keywords_count">0 active keywords</span>)
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
      <div uk-grid class="mt-space">
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" keywords_up">0</h6>
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
                  <h6 class=" top-three">0/0</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 3</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-three-since"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-ten">0/0</h6>

                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 10</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-ten-since"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-twenty">0/0</h6>

                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 20</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-twenty-since"><i class="icon ion-arrow-up-a"></i><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" top-thirty">0/0</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 30</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-thirty-since"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-3">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class="top-hundred">0/0</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 100</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="top-hundred-since"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
      </div>
   </div>

<div class="white-box-body">
   <div class="table-box">
      <div class="white-box pa-0">
         <div class="project-table-cover box-boxshadow">
            <div class="project-table-body LiveKeywordTable" id="LiveKeywordTableDiv">
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
                        <th class="liveKeyword_sorting" width="70" data-sorting_type="asc" data-column_name="life_ranking">
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
                        $pages = 10;
                     }
                  }else{
                     $pages = 10;
                  }

                  $img = $value->get_flag_data($value->region);

                  ?>

                  <tr class="<?php if($value->is_favorite == 1){ echo ' active';}?>">
                    <!--<tr class="<?php if($key%2 == 0){ echo 'even';}else{ echo 'odd';} if($value->is_favorite == 1){ echo ' active';}?>">-->
                     <td>
                        <div class="flex">
                           <i class="fa fa-star"></i>
                           @if(!empty($img))
                           <figure class="keyword-flag-icon" uk-tooltip="title: {{$value->region}}; pos: top-left">
                              <img src="{{$img}}">
                           </figure>
                           @endif
                                       <!-- <figure class="location-icon" uk-tooltip="title: {{$value->canonical}}; pos: top-left">
                                          <img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}">
                                       </figure> -->
                                       <h6 uk-tooltip="title: {{$value->keyword}}; pos: top-left"><a href="javascript:;">{{$value->keyword}}</a>
                                       </h6>
                                   <!--  <div class="icons-list fixed">
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
                                    </div> -->
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
                              <td class="<?php if($pages > 10){ echo 'grey';}?>">{{$pages}}</td>
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
                              @if(!in_array('oneday',$table_settings))
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

                 <input type="hidden" name="hidden_page_liveKeyword" id="hidden_page_liveKeyword" value="1" />
                 <input type="hidden" name="hidden_column_name_liveKeyword" id="hidden_column_name_liveKeyword" value="currentPosition" />
                 <input type="hidden" name="hidden_sort_type_liveKeyword" id="hidden_sort_type_liveKeyword" value="asc" />
                 <input type="hidden" name="limit_liveKeyword" id="limit_liveKeyword" value="200" />

              </div>
           </div>
        </div>
     </div>
  </div>
<!-- Live Keyword Tracking Row End