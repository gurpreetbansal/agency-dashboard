@if(isset($results) && !empty($results))
@foreach($results  as $key=>$result)
<tr class="<?php if($key%2 == 0){ echo 'odd';}else{ echo 'even';}?>">
  <td>{{@$result->device}}</td>
  <td>{{@$result->impressions}}</td>
  <td>{{@$result->clicks}}</td>
  <td>{{number_format(@$result->ctr,2,'.','')}}</td>
  <td>{{'$'.number_format(@$result->cost,2,'.','')}}</td>
  <td>{{@$result->conversions}}</td>
</tr>
@endforeach
@endif