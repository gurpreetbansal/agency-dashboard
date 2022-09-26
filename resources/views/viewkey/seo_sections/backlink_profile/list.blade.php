 <input type="hidden" class="campaignID" value="{{$campaign_id}}">

 @if(isset($backlink_records) && !empty($backlink_records) && count($backlink_records) > 0)
 @foreach($backlink_records as $key=> $record)


 <?php if(strlen($record->url_from) > 50){
  $url_from = substr($record->url_from,0,50)."...";
}
else{
  $url_from = $record->url_from;
}



if(strlen($record->url_to) > 50){
  $url_to = substr($record->url_to,0,50)."...";
}
else{
  $url_to = $record->url_to;
}

if(strlen($record->title) > 50){
  $title = substr($record->title,0,50)."...";
}
else{
  $title = $record->title;
}

if(strlen($record->link_text) > 50){
  $link_text = substr($record->link_text,0,50)."...";
}
else{
  $link_text = $record->link_text;
}
?>



<tr class="<?php if($key%2==0){ echo 'odd';}else{ echo 'even';}?>">
  <td>
    <h6><a href="javascript:;">{{$title}}</a></h6>
    <p><strong>Source:</strong>
      <a href="{{$record->url_from}}" target="_blank" class="fx-width">{{$url_from}}</a>
      <a href="{{$record->url_from}}" target="_blank"> <i class="fa fa-external-link"></i></a>
    </p>
    <p><strong>Target:</strong>
      <a href="{{$record->url_to}}" target="_blank" class="fx-width">{{$url_to}}</a>
      <a href="{{$record->url_to}}" target="_blank"> <i class="fa fa-external-link"></i></a>
    </p>
    <span class="follow-status"><?php if($record->nofollow == 'follow'){echo 'F';}else{ echo 'NF';}?></span>
  </td>
  <td>{{$record->link_type}}</td>
  <td>{{$link_text}}</td>
  <td>{{$record->links_ext}}</td>
  <td>{{date('F d, Y',strtotime($record->first_seen))}}</td>
  <td>{{date('F d, Y',strtotime($record->last_visited))}}</td>
</tr>
@endforeach
@else
<tr><td colspan="6"><center>No Backlink found</center></td></tr>
@endif