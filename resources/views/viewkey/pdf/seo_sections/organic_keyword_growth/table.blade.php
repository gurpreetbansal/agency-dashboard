@if(isset($keywords) && !empty($keywords))
@foreach($keywords as $keyword)
<tr>
  <td><a href="{{$keyword->url}}">{{$keyword->keywords}}</a></td>
  <td>{{$keyword->position}}</td>
  <td>{{$keyword->search_volume}}</td>
  <td>{{number_format($keyword->cpc,2)}}</td>
  <td>{{number_format($keyword->traffic,2)}}</td>
</tr>
@endforeach
@endif