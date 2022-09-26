@isset($campaign_data)
@foreach($campaign_data as $key=>$value)
<tr <?php if($value->check_time_diff($value->created) == 'loader'){ echo "class='disable_project'" ;}?>  id="check_class">
    <td class="ajax-loader">
        @if($value->check_time_diff($value->created) == 'loader')
        <input type="hidden" class="timer" value="{{$value->created}}">
        @endif
        <div class="flex">
            <figure class="project-icon">
                @if(@$value->favicon)
                <a href="#"><img src="{{@$value->favicon}}"></a>
                @endif
            </figure>
            <h6 uk-tooltip="title: {{@$value->domain_name}}; pos: top-left"><a href="{{url('/campaign-detail/'.$value->id)}}">{{@$value->domain_name}}</a></h6>
            <div class="icons-list">
                <a href="#" uk-tooltip="title:Google Analytics; pos: top-center" class="<?php if(empty($value->google_analytics_id)){ echo 'inactive'; }?>"><img
                    src='{{URL::asset("/public/vendor/internal-pages/images/chart-icon.png")}}'></a>
                    <a href="#"
                    uk-tooltip="title:Google Search Console; pos: top-center" class="<?php if(empty($value->console_account_id)){ echo 'inactive'; }?>"><img
                    src='{{URL::asset("/public/vendor/internal-pages/images/search-console-img.png")}}'></a>
                    <a href="#" uk-tooltip="title:Google Adwords; pos: top-center" class="<?php if(empty($value->google_ads_campaign_id)){ echo 'inactive'; }?>"><img src='{{URL::asset("/public/vendor/internal-pages/images/adword-icon.png")}}'></a>

                   @if($value->check_time_diff($value->created) == 'loader')<a href="javascript:;" class="NewProLoader" ><img src='{{URL::asset("/public/vendor/internal-pages/images/campaign-loader.gif")}}'></a>@endif
                    </div>
                    <div class="tag-list">

                        @if(!empty($value->get_campaign_tags($value->id)))
                        @foreach($value->get_campaign_tags($value->id) as $count=> $tag)
                        <span>{{$tag}}</span>
                        @if($count == 1)
                        @break;
                        @endif
                        @endforeach
                        @endif
                    </div>
                    @if(@$value->get_manager_image($value->id) !='')
                    <figure uk-tooltip="title:{{@$value->get_manager_name($value->id)}}; pos: top-center"
                        class="project-manager"><img src="{{@$value->get_manager_image($value->id)}}"></figure>
                        @endif
                    </div>
                </td>

                <td class="ajax-loader">
                    <img src='{{URL::asset("/public/vendor/images/google-logo-icon.png")}}' class="search-eng-icon">{{@$value['location']}}
                    {{@$value->get_regional_db_location($value->regional_db)}}
                </td>
                <td class="ajax-loader">
                    <figure class="flag-icon">
                        <img src="{{@$value->get_regional_db_flag($value->regional_db)}}">
                    </figure>
                </td>
                <td class="ajax-loader">
                    @if(!empty($value->get_campaign_data->keywords_count))
                    {{@$value->get_campaign_data->keywords_count}}
                    @else
                    0
                    @endif
                    <!-- | <span class="{{@$avg_color}}">{{@$value->get_campaign_data->keyword_avg}}<span uk-icon="{{@$avg_arrow}}"></span></span> -->
                </td>

                <?php
                $since_hundred = $since_twenty = $since_ten = $since_three = '';
                $hundred_span = $twenty_span = $ten_span = $three_span  = '';
                $keyword_stats = $value->keyword_stats($value->id);
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
       <td class="ajax-loader">{{@$value->get_campaign_data->top_three}}{!!$three_span!!}{{$since_three}}</td>
       <td class="ajax-loader">{{@$value->get_campaign_data->top_ten}}{!!$ten_span!!}{{$since_ten}}</td>
       <td class="ajax-loader">{{@$value->get_campaign_data->top_twenty}}{!!$twenty_span!!}{{$since_twenty}}</td>
       <td class="ajax-loader">{{@$value->get_campaign_data->top_hundred}}{!!$hundred_span!!}{{$since_hundred}}</td>
       <td class="ajax-loader">{{@$value->get_campaign_data->backlinks_count}}</td>
       <td class="ajax-loader">
        @if(@$value->getUserRole($value->user_id) == 2)
        <div class="btn-group">
            <a href="javascript:;" data-id="{{@$value->id}}"  id="ShareKey" class="btn small-btn icon-btn color-purple"
                uk-tooltip="title:Generate Shared Key; pos: top-center">
                <img src='{{URL::asset("/public/vendor/internal-pages/images/share-icon-small.png")}}'>
            </a>
            <a href='{{url("/project-settings/".@$value->id)}}' class="btn small-btn icon-btn color-orange"
                uk-tooltip="title:Project Setting; pos: top-center">
                <img src='{{URL::asset("/public/vendor/internal-pages/images/setting-icon-small.png")}}'>
            </a>
            <a href="javascript:;" data-id="{{@$value->id}}" data-name="{{@$value->domain_name}}" data-url="{{@$value->domain_url}}" class="btn small-btn icon-btn color-red delete_project"
                uk-tooltip="title:Delete Project; pos: top-center">
                <img src='{{URL::asset("/public/vendor/internal-pages/images/delete-icon-small.png")}}'>
            </a>
            <a href="javascript:;" data-id="{{@$value->id}}" data-name="{{@$value->domain_name}}" data-url="{{@$value->domain_url}}" class="btn small-btn icon-btn color-blue archive_row"
                uk-tooltip="title:Archive Project; pos: top-center">
                <img src='{{URL::asset("/public/vendor/internal-pages/images/archive-icon-small.png")}}'>
            </a>
            <a href="javascript:;" data-id="{{@$value->id}}" data-name="{{@$value->domain_name}}" data-url="{{@$value->domain_url}}" class="btn small-btn icon-btn color-red favourite_row"
                uk-tooltip="title:Favourite Project; pos: top-center">
                @if($value->is_favorite==0)
                <i class="fa fa-heart-o"></i>
                @else
                <i class="fa fa-heart"></i>
                @endif
            </a>
        </div>
        @endif
    </td>
    @if(@$value->getUserRole($value->user_id) != 4)
    <td class="ajax-loader">
        <input class="uk-checkbox selected_campaigns" type="checkbox" value="{{$value->id}}" name="selected_campaigns[]">
    </td>
    @endif
</tr>



@endforeach
@endif