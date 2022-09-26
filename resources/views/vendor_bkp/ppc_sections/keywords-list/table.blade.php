@if(isset($results) && !empty($results) && count($results) > 0)
<?php $evenOdd = 0; ?>
@foreach($results  as $key=>$result)
<tr class="<?php if($evenOdd%2 == 0){ echo 'odd';}else{ echo 'even';}?>">
  <td>{{@$result['name'] <> '' ? $result['name'] : '---' }}</td>
  <td>{{@$result['impressions']}}</td>
  <td>{{@$result['clicks']}}</td>
  <td>{{number_format(@$result['ctr'],2,'.','')}}%</td>
  <td>{{'$'.number_format(@$result['cost'],2,'.','')}}</td>
  <td>{{@$result['conversions']}}</td>

</tr>
<?php $evenOdd++; ?>
@endforeach
@else
<tr >
  <td colspan="6" ><center>No keywords found </center> </td>
</tr>
@endif