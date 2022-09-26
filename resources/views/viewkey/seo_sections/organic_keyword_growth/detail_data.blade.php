<input type="hidden" class="campaignID" value="{{$campaign_id}}">

@if(isset($keywords) && !empty($keywords))
@foreach($keywords as  $keyword)
<!-- 1 Data row -->
<tr class="odd">
	<td>{{$keyword->keywords}}</td>
	<td>{{$keyword->position}}</td>
	<td>{{$keyword->search_volume}}</td>
	<td>{{number_format($keyword->cpc,2)}}</td>
	<td>{{number_format($keyword->traffic,2)}}</td>
</tr>
@endforeach
@endif