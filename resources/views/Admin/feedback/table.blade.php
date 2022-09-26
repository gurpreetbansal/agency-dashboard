@if(isset($data) && ($data->total() > 0))
@foreach($data as $key=>$value)
<tr>
    <td class="ajax-loader">{{$value->user_info->name}}</td>
    <td class="ajax-loader">{{$value->overall_rating}}</td>
    <td class="ajax-loader">{{$value->recommend}}</td>
    <td class="ajax-loader"><?php if(strlen($value->description) > 50){ echo substr($value->description, 0, 50).' ...';}else{ echo substr($value->description, 0, 50);}?></td>
    <td class="ajax-loader">
        <div class="btn-group">
            <a data-id="{{$value->id}}" href="javascript:;" class="btn small-btn icon-btn color-orange show-client_feedback" uk-toggle="target: #show-cancel-feedback"><span uk-icon="pencil"></span></a>
        </div>
    </td>     
</tr>
@endforeach
@else
<tr><td colspan="4"><center>No data found</center></td></tr>
@endif