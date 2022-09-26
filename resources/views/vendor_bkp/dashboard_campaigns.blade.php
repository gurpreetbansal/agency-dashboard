@if(isset($campaign_data) && ($campaign_data->total() >0))
@foreach($campaign_data as $key=>$value)

<tr class="<?php if($value->check_time_diff == 'loader'){ echo "disable_project" ;}?> <?php if($value->is_favorite == 1){ echo ' favourite_campaign';}?>"  id="check_class">
    <td class="ajax-loader">
        @if($value->check_time_diff == 'loader')
        <input type="hidden" class="timer" value="{{$value->created}}">
        @endif
        <div class="flex">
            <figure class="project-icon">
                @if(@$value->favicon)
                <a href="{{@$value->campaign_url}}"  target="_blank"><img src="{{@$value->favicon}}"><i class="fa fa-external-link" style="display: none;"></i></a>
                @endif
            </figure>
            <h6 uk-tooltip="title: {{@$value->domain_name}}; pos: top-left"><a href="{{url('/campaign-detail/'.$value->id)}}">{{@$value->host_url}}</a>
            </h6>
<!-- 
            @if($value->check_time_diff == 'loader')
            <a href="javascript:;" class="NewProLoader" >
                <img src='{{URL::asset("/public/vendor/internal-pages/images/campaign-loader.gif")}}'>
            </a>
            @endif -->
            <div class="tag-list">

                @if(!empty($value->campaign_tag))
                @foreach($value->campaign_tag as $count=> $tag)
                <span uk-tooltip="title:{{$tag}}; pos: top-center"><?php
                if (strlen($tag) > 5){
                  echo substr($tag, 0, 5) . '...';
              }else{
               echo $tag;
           }
           ?></span>
           @endforeach
           @endif
       </div>
       @if(Auth::user()->role_id == 2)
       @if(@$value->get_manager_image($value,$value->id) <> null)
       <?php echo $value->manager_details;?>
       @endif
       @endif
   </div>
</td>
<td>{{date("M d, Y",strtotime($value->domain_register))}}</td>
<td>
    <div class="icons-list">
        @if (in_array("1", explode(',',$value->dashboard_type)))
        <a href="javascript:;" uk-tooltip="title:Google Analytics; pos: top-center" class="<?php if(empty($value->google_analytics_id)){ echo 'inactive'; }?>"><img
            src='{{URL::asset("/public/vendor/internal-pages/images/organic-traffic-growth-img.png")}}'></a>
            <a href="javascript:;"
            uk-tooltip="title:Google Search Console; pos: top-center" class="<?php if(empty($value->console_account_id)){ echo 'inactive'; }?>"><img
            src='{{URL::asset("/public/vendor/internal-pages/images/search-console-img.png")}}'></a>
            @endif
            @if (in_array("2", explode(',',$value->dashboard_type)))
            <a href="javascript:;" uk-tooltip="title:Google Adwords; pos: top-center" class="<?php if(empty($value->google_ads_campaign_id)){ echo 'inactive'; }?>"><img src='{{URL::asset("/public/vendor/internal-pages/images/google_adwords_icon.png")}}'></a>
            @endif
            @if (in_array("3", explode(',',$value->dashboard_type)))
            <a href="javascript:;" uk-tooltip="title:Google My Business; pos: top-center" class="<?php if(empty($value->gmb_analytics_id) && empty($value->gmb_id)){ echo 'inactive'; }?>"><img src='{{URL::asset("/public/vendor/internal-pages/images/google-my-business-icon-img.png")}}'></a>
            @endif


        </div>
    </td>

    <td class="ajax-loader">
        <img src='{{URL::asset("/public/vendor/images/google-logo-icon.png")}}' class="search-eng-icon">{{@$value['location']}}
        {{@$value->regional_db}}
    </td>
    <td class="ajax-loader">
        <figure class="flag-icon">
            <img src="{{ @$value->regional_db_flag }}">
        </figure>
    </td>
    <td class="ajax-loader">
        @if(!empty($value->keywords_count))
        {{@$value->keywords_count}}
        @else
        0
        @endif
        <!-- | <span class="{{@$avg_color}}">{{@$value->get_campaign_data->keyword_avg}}<span uk-icon="{{@$avg_arrow}}"></span></span> -->
    </td>

    <?php

    $since_hundred = $since_twenty = $since_ten = $since_three = '';
    $hundred_span = $twenty_span = $ten_span = $three_span  = '';
    $keyword_stats = $value->keyword_stats;
    if(!empty($keyword_stats->since_hundred) && $keyword_stats->since_hundred > 0){
        $since_hundred = $keyword_stats->since_hundred;
        $hundred_span = '<span class="green"><span uk-icon="arrow-up" class="ma-0"></span>';
    }elseif($keyword_stats->since_hundred < 0){
        $hundred_span = '<span class="red"><span uk-icon="arrow-down" class="ma-0"></span>';
    }
    if(!empty($keyword_stats->since_twenty) && $keyword_stats->since_twenty > 0){
        $since_twenty = $keyword_stats->since_twenty;
        $twenty_span = '<span class="green"><span uk-icon="arrow-up" class="ma-0"></span>';
    }elseif($keyword_stats->since_twenty < 0){
     $twenty_span = '<span class="red"><span uk-icon="arrow-down" class="ma-0"></span>';
 }
 if(!empty($keyword_stats->since_ten) && $keyword_stats->since_ten > 0){
    $since_ten = $keyword_stats->since_ten;
    $ten_span = '<span class="green"><span uk-icon="arrow-up" class="ma-0"></span>';
}elseif($keyword_stats->since_ten < 0){
 $ten_span = '<span class="red"><span uk-icon="arrow-down" class="ma-0"></span>';
}
if(!empty($keyword_stats->since_three) && $keyword_stats->since_three > 0){
    $since_three = $keyword_stats->since_three;
    $three_span = '<span class="green"><span uk-icon="arrow-up" class="ma-0"></span>';
}elseif($keyword_stats->since_three < 0){
    $three_span = '<span class="red"><span uk-icon="arrow-down" class="ma-0"></span>';
}
?>
<td class="ajax-loader">{{@$value->top_three}}{!!$three_span!!}{{$since_three}}</td>
<td class="ajax-loader">{{@$value->top_ten}}{!!$ten_span!!}{{$since_ten}}</td>
<td class="ajax-loader">{{@$value->top_twenty}}{!!$twenty_span!!}{{$since_twenty}}</td>
<td class="ajax-loader">{{@$value->top_hundred}}{!!$hundred_span!!}{{$since_hundred}}</td>
<td class="ajax-loader">{{@$value->backlinks_count}}</td>
@if(Auth::user()->role_id != 4)
<td class="ajax-loader">
    <div class="btn-group">
        <a href="javascript:;" class="btn small-btn icon-btn color-orange actionBtn" data-audit-status="{{@$value->site_audit_status}}" data-id="{{@$value->id}}" data-name="{{@$value->domain_name}}" data-url="{{@$value->domain_url}}" data-favourite="{{@$value->is_favorite}}"><i class="fa fa-ellipsis-h"></i></a>  
    </div>
</td>
@endif

@if(Auth::user()->role_id != 4)
<td class="ajax-loader">
    <input class="uk-checkbox selected_campaigns" type="checkbox" value="{{$value->id}}" name="selected_campaigns[]">
</td>
@endif
</tr>



@endforeach
@else
<tr><td colspan="12"><center>No projects found.</center></td></tr>
@endif