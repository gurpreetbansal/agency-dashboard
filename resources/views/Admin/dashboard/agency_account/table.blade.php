@if(isset($data) && ($data->total() > 0))
@foreach($data as $key=>$value)
<tr>
    <td class="ajax-loader">
       <div class="flex">
        <figure class="project-icon">
            @if(@$value->favicon)
            <a href="{{@$value->campaign_url}}"  target="_blank"><img src="{{@$value->favicon}}"><i class="fa fa-external-link" style="display: none;"></i></a>
            @endif
        </figure>
        {{$value->host_url}}
        @if($value->clientName <> null)
        {{' ('.$value->clientName.')'}}
        @endif
    </div>
</td>
<td class="ajax-loader">{{date('F d, Y',strtotime($value->domain_register))}}</td>
<td class="ajax-loader"> 
<?php 
if($value->get_manager_image($value,$value->id) <> null){
       echo $value->manager_details;
}?>
</td>
<td class="ajax-loader">
<?php 
if($value->get_client_image($value,$value->id) <> null){
       echo $value->client_details;
}?>
</td>
<td class="ajax-loader"><a href="{{$value->get_viewkey_link($value->user_id,$value->id)}}" target="_blank"><i class="fa fa-external-link"></i></a></td>
<td class="ajax-loader"><?php if($value->status == 0){ echo 'Active';}elseif($value->status ==1){echo 'Archived';}elseif($value->status ==2){echo 'Deleted';}?></td>
<td class="ajax-loader">
   <div class="icons-list">
        @if (in_array("1", explode(',',$value->dashboard_type)))
            <a href="javascript:;" uk-tooltip="title:Google Analytics; pos: top-center" class="<?php if(empty($value->google_analytics_id)){ echo 'inactive'; }?> <?php if($value->get_project_errors($value->id,1) == 1){ echo 'blink';}?>"><img
            src='{{URL::asset("/public/vendor/internal-pages/images/organic-traffic-growth-img.png")}}'></a>
            <a href="javascript:;"
            uk-tooltip="title:Google Search Console; pos: top-center" class="<?php if(empty($value->console_account_id)){ echo 'inactive'; }?><?php if($value->get_project_errors($value->id,2) == 1){ echo 'blink';}?>"><img
            src='{{URL::asset("/public/vendor/internal-pages/images/search-console-img.png")}}'></a>
            @endif
            @if (in_array("2", explode(',',$value->dashboard_type)))
            <a href="javascript:;" uk-tooltip="title:Google Adwords; pos: top-center" class="<?php if(empty($value->google_ads_campaign_id)){ echo 'inactive'; }?><?php if($value->get_project_errors($value->id,3) == 1){ echo 'blink';}?>"><img src='{{URL::asset("/public/vendor/internal-pages/images/google_adwords_icon.png")}}'></a>
            @endif
            @if (in_array("3", explode(',',$value->dashboard_type)))
            <a href="javascript:;" uk-tooltip="title:Google My Business; pos: top-center" class="<?php if(empty($value->gmb_analytics_id) && empty($value->gmb_id)){ echo 'inactive'; }?><?php if($value->get_project_errors($value->id,4) == 1){ echo 'blink';}?>"><img src='{{URL::asset("/public/vendor/internal-pages/images/google-my-business-icon-img.png")}}'></a>
            @endif


        </div>
</td>
</tr>
@endforeach
@else
<tr><td colspan="5"><center>No records found</center></td></tr>
@endif