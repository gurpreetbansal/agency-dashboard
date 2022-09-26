<input type="hidden" class="campaignID" value="{{$campaign_id}}">

@if(isset($keywords) && !empty($keywords) && count($keywords) > 0)
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
@else
<tr><td colspan="5"><center>No data found</center></td></tr>
@endif