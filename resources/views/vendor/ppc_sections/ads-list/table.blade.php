@if(isset($results) && !empty($results)  && count($results) > 0)
<?php $evenOdd = 0; ?>
@foreach($results as $key=>$result)
<tr class="<?php if($evenOdd%2 == 0){ echo 'odd';}else{ echo 'even';}?>">
  <td>
    <article>
      <h6>
        <small>
          <a
          href="javascript:;">{{ @$result['displayurl'] }}</a>
        </small>
        
        <a href="javascript:;">{{ @$result['name'] }}</a>
        
      </h6>
      <p>{{ @$result['description'] }}</p>
    </article>
  </td>
  <td>{{@$result['ad_type']}}</td>
  <td>{{@$result['impressions']}}</td>
  <td>{{@$result['clicks']}}</td>
  <td>{{number_format(@$result['ctr'],2,'.','')}}%</td>
  <td>{{ number_format(@$result['cost'],2,'.','') }}</td>
  <td>{{@$result['conversions']}}</td>
</tr>
<?php $evenOdd++; ?>
@endforeach
@else
<tr >
  <td colspan="7" ><center>No ads found </center> </td>
</tr>
@endif