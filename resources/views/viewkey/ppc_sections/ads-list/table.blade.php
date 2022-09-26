@if(isset($results) && !empty($results))
@foreach($results as $key=>$result)
<tr class="{{ ($key%2==0) ? 'even':'odd' }}">
  <td>
    <article>
      <h6>
        <a href="#">{{@$result->ad_headline($result->ad_type,$result)}}</a>
        <small>
          <a
          href="#">{{@$result->ad_display_url($result->ad_type,$result)}}</a>
        </small>
      </h6>
      <p>{{@$result->ad_description($result->ad_type,$result)}}</p>
    </article>
  </td>
  <td>{{@$result->ad_type}}</td>
  <td>{{@$result->impression}}</td>
  <td>{{@$result->click}}</td>
  <td>{{number_format(@$result->ctr,2,'.','')}}</td>
  <td>{{'$'.@$result->costs}}</td>
  <td>{{@$result->conversion}}</td>
</tr>
@endforeach
@endif