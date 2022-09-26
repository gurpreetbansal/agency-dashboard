@if(count($campaign_data))
@foreach($campaign_data as $key=>$value)
<tr>
    <td>
        <div class="flex">
            <figure class="project-icon">
             @if(@$value->favicon)
             <a href="{{@$value->domain_url}}"  target="_blank"><img src="{{@$value->favicon}}"><i class="fa fa-external-link" style="display: none;"></i></a>
             @endif
         </figure>
         <h6 uk-tooltip="title: {{@$value->domain_name}}; pos: top-left"><a href="javascript:;">{{@$value->host_url}}</a></h6>
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
               @if(Auth::user()->role_id == 2)  
                    @if(@$value->get_manager_image($value,$value->id) <> null)  
                        <?php echo $value->get_manager_image($value,$value->id);?>  
                    @endif  
                @endif
                </div>
            </td>
            <td>
                <div class="icons-list">
                    @if (in_array("1", explode(',',$value->dashboard_type)))
                    <a href="javascript:;" uk-tooltip="title:Google Analytics; pos: top-center" class="<?php if(empty($value->google_analytics_id)){ echo 'inactive'; }?>"><img
                    src='{{URL::asset("/public/vendor/internal-pages/images/google_analytics_icon.png")}}'></a>
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

            <td>
                <img src='{{URL::asset("/public/vendor/images/google-logo-icon.png")}}' class="search-eng-icon">{{@$value['location']}}
                {{@$value->get_regional_db_location($value->regional_db)}}
            </td>
            <td>
                <figure class="flag-icon">
                    <img src="{{@$value->get_regional_db_flag($value->regional_db)}}">
                </figure>
            </td>
            <td>
                <?php 
                $avg_arrow = $avg_color = '';
                if(@$value->get_campaign_data->keyword_avg > 0){
                    $avg_arrow = 'arrow-up';
                    $avg_color = 'green';
                }elseif(@$value->get_campaign_data->keyword_avg < 0){
                    $avg_arrow = 'arrow-down';
                    $avg_color = 'red';
                }            
                ?>

                @if(!empty($value->get_campaign_data->keywords_count))
                {{@$value->get_campaign_data->keywords_count}} 
                @else
                0
                @endif
                <!-- | <span class="{{@$avg_color}}">{{@$value->get_campaign_data->keyword_avg}}<span uk-icon="{{@$avg_arrow}}"></span></span> -->
            </td>
            <td>{{@$value->get_campaign_data->top_three}}</td>
            <td>{{@$value->get_campaign_data->top_ten}}</td>
            <td>{{@$value->get_campaign_data->top_twenty}}</td>
            <td>{{@$value->get_campaign_data->top_hundred}}</td>
            <td>{{@$value->get_campaign_data->backlinks_count}}</td>
            <td>
                @if(@$value->getUserRole($value->user_id) == 2)
                <div class="btn-group">      
                @if(Auth::user()->role_id == 2)                 
                    <a href="javascript:;" data-id="{{@$value->id}}" data-name="{{@$value->domain_name}}" data-url="{{@$value->domain_url}}" class="btn small-btn icon-btn color-red delete_archived_project"
                        uk-tooltip="title:Delete Campaign; pos: top-center">
                        <img src='{{URL::asset("/public/vendor/internal-pages/images/delete-icon-small.png")}}'>
                    </a>
                    @endif
                    <a href="javascript:;" data-id="{{@$value->id}}" data-name="{{@$value->domain_name}}" data-url="{{@$value->domain_url}}" class="btn small-btn icon-btn color-orange restore_row"
                        uk-tooltip="title:Restore Campaign; pos: top-center">
                        <img src='{{URL::asset("/public/vendor/internal-pages/images/restore-icon.png")}}'>
                    </a>
                </div>
                @endif
            </td>
            <td>
                <input class="uk-checkbox selected_archived_campaigns" type="checkbox" value="{{$value->id}}" name="selected_archived_campaigns[]">
            </td>
        </tr>
        @endforeach

        @else
        <tr>
            <td colspan="10" style="text-align: center;">No Archived campaigns</td>
        </tr>
        @endif