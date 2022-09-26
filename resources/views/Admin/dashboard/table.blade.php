@if(isset($data) && ($data->total() > 0))
@foreach($data as $key=>$value)
<tr>
 <td class="ajax-loader">
    @if($value->subscription_status == 1)
    <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}" alt="check-icon">
    @elseif($value->subscription_status == 0)
    <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}" alt="cross-icon">
    @endif
</td>
<td class="ajax-loader">{{$key+1}}</td>
<td class="ajax-loader">{{@$value->email}}</td>
<td class="ajax-loader">
    <div class="flex">
        @if(@$value->get_account_user_image($value->id) !='')
        <?php echo $value->get_account_user_image($value->id);?>
        @endif
        <h6>{{@$value->company_name .'- (' .@$value->name.')'}}</h6>
    </div>
 </td>
    
<td class="ajax-loader"><?php echo $value->get_project_data($value->id)['used_projects'].'/'.$value->get_project_data($value->id)['package_projects'];?></td>
<td class="ajax-loader"><?php echo $value->get_keywords_data($value->id)['used_keywords'].'/'.$value->get_keywords_data($value->id)['package_keywords'];?></td>
<td class="ajax-loader"><?php echo $value->get_user_active_package($value->id);?></td>
 <td class="ajax-loader"><?php if(!empty($value->last_login)){ echo date('F d, Y H:i:s',strtotime($value->last_login)); }else{ echo '-';}?></td>
<td class="ajax-loader">{{date('F d, Y H:i:s',strtotime($value->created_at))}}</td>
<td class="ajax-loader">
    <div class="btn-group">
        <a href="javascript:;" data-id="{{@$value->id}}" class="btn small-btn icon-btn color-orange loginAsClient" uk-tooltip="title:Login to User Account; pos: top-center"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
        <a href="{{url('/admin/agency-account-details/'.$value->id)}}" class="btn small-btn icon-btn color-orange" uk-tooltip="title:View account details; pos: top-center"><img src="{{URL::asset('public/vendor/internal-pages/images/account-details.png')}}" alt="user account details"></a>
    </div>
</td>        
</tr>
@endforeach
@else
<tr><td colspan="7"><center>No record found</center></td></tr>
@endif