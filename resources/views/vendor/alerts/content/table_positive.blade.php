@if(isset($alerts) && !empty($alerts) && ($alerts->total()  > 0))
@foreach($alerts as $key=>$value)
<tr <?php if($value->oneday_position < 0){ echo 'class="down-rank"';}elseif($value->oneday_position > 0){ echo 'class="up-rank"';}?>>
    <td>{{$value->calculated_time}}</td>
    <td><a href="{{url('/campaign-detail/'.$value->request_id)}}" target="_blank">{{$value->project_name}}</a></td>
    <td><a href="{{$value->url_site}}" target="_blank"><?php if(strlen($value->url_site) > 20){ echo substr($value->url_site,0,20).'...';}else{ echo $value->url_site;}?></a></td>
    <td><img src="{{$value->regional_flag}}"> {{$value->keyword}}</td>
    <td> {{$value->sv}}</td>
    <td>{{($value->position + $value->oneday_position)}}</td>
    <td>
     <?php
        if($value->position === 0 || $value->position === null){ echo "<span class='grey'>>100</span>";}else{ echo $value->position ;}
        if($value->oneday_position < 0){ 
            echo '<i class="icon ion-arrow-down-a inline"></i>'.str_replace('-','',$value->oneday_position);
        }elseif($value->oneday_position > 0){ 
            echo '<i class="icon ion-arrow-up-a inline"></i>'.$value->oneday_position;
        } 
        ?>
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="7"><center>No alerts data</center></td>
</tr>
@endif