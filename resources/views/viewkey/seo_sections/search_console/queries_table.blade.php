@if(isset($query_data) && count($query_data) > 0)
@foreach($query_data as $value)
<tr>
	<td>{{$value['queries']}}</td>
	<td>{{$value['clicks']}}</td>
	<td>{{$value['impressions']}}</td>
	<td>{{number_format($value['ctr'],2)}}</td>
	<td>{{number_format($value['position'],2)}}</td>
</tr>
@endforeach
@include('viewkey.seo_sections.search_console.queries_pagination')
@else
<center><tr><td colspan="5">No query found</td></tr></center>
@endif