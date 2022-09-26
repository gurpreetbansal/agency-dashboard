@if(isset($data) && ($data->total() > 0))
@foreach($data as $key=>$value)
<tr>
    <td class="ajax-loader">
        <?php
        if($value->category == 1){
            echo 'Critical';
        }elseif($value->category == 2){
            echo 'Warning';
        }elseif($value->category == 3){
            echo 'Notice';
        }
        ?>
    </td>
    <td class="ajax-loader">{{$value->error_key}}</td>
    <td class="ajax-loader">{{$value->error_label}}</td>
    <td class="ajax-loader">
        <div class="btn-group">
            <a href="{{url('/admin/site-audit/edit/'.$value->id)}}" class="btn small-btn icon-btn color-orange"><span uk-icon="pencil"></span></a>
            <a href="{{url('/admin/site-audit/destroy/'.$value->id)}}"  class="btn small-btn icon-btn color-orange"><span uk-icon="trash"></span></a>
        </div>
    </td>        
</tr>
@endforeach
@else
<tr><td colspan="4"><center>No data found</center></td></tr>
@endif