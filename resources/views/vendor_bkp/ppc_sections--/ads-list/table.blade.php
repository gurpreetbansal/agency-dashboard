@if(isset($results) && !empty($results)  && count($results) > 0)
<?php $evenOdd = 0; ?>
@foreach($results as $key=>$result)
<tr class="<?php if($evenOdd%2 == 0){ echo 'odd';}else{ echo 'even';}?>">
  <td>
    <article>
      <h6>
        <small>
          <a
          href="javascript:;">{{ @$addiionalData[$result['adId']]['displayurl'] }}</a>
        </small>
        @if(isset($addiionalData[$result['adId']]['headlines']) && trim($addiionalData[$result['adId']]['headlines']) <> '')
        <a href="javascript:;">{{ @$addiionalData[$result['adId']]['headlines'] }}</a>
        @endif
       
      </h6>
      <p>{{ @$addiionalData[$result['adId']]['discription'] }}</p>
    </article>
  </td>
  <td>{{@$addiionalData[$result['adId']]['ad_type']}}</td>
  <td>{{@$result['impressions']}}</td>
  <td>{{@$result['clicks']}}</td>
  <td>{{number_format(@$result['ctr'],2,'.','')}}%</td>
  <td>{{'$'.@$result['cost']}}</td>
  <td>{{@$result['conversions']}}</td>
</tr>
<?php $evenOdd++; ?>
@endforeach
@else
<tr >
  <td colspan="7" ><center>No ads found </center> </td>
</tr>
@endif