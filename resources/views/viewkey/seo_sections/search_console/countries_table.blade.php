@if(isset($country_data) && count($country_data) > 0)
@foreach($country_data as $value)
@php 
$country = App\SearchConsoleUsers::country_detail($value['country']);
@endphp
<tr>
	<td>
		@if($country['flag'] !== '')
		<img src="{{URL::asset('/public/flags/'.$country['flag'])}}">
		@endif
	{{$country['full_name']}}</td>
	<td>{{$value['clicks']}}</td>
	<td>{{$value['impressions']}}</td>
	<td>{{number_format($value['ctr'],2)}}</td>
	<td>{{number_format($value['position'],2)}}</td>
</tr>
@endforeach
@include('viewkey.seo_sections.search_console.countries_pagination')
@else
<center><tr><td colspan="5">No country found</td></tr></center>
@endif