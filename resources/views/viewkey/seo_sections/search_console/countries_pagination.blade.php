<tr><td colspan="5">
  <div class="project-table-cover">
  <div class="project-table-foot" id="countries-foot">

<div class="project-entries">
 <p>Showing {{$country_data->firstItem() }} to {{ $country_data->lastItem() }} of {{ $country_data->total() }} entries</p>
</div>
<div class="pagination countries-pagination">
 @if ($country_data->hasPages())
 <ul class="pagination" role="navigation">
  @if ($country_data->onFirstPage())
  <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
   <span class="page-link" aria-hidden="true">Previous</span>
 </li>
 @else
 <li class="page-item">
   <a class="page-link" href="{{ $country_data->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
 </li>
 @endif
 <?php
 $start = $country_data->currentPage() - 2; 
 $end = $country_data->currentPage() + 2; 
 if($start < 1) {
   $start = 1; 
   $end += 1;
 } 
 if($end >= $country_data->lastPage() ) $end = $country_data->lastPage(); 
 ?>
 @if($start > 1)
 <li class="page-item">
   <a class="page-link" href="{{ $country_data->url(1) }}">{{1}}</a>
 </li>
 @if($country_data->currentPage() != 4)
 <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
 @endif
 @endif
 @for ($i = $start; $i <= $end; $i++)
 <li class="page-item {{ ($country_data->currentPage() == $i) ? ' active' : '' }}">
   <a class="page-link" href="{{ $country_data->url($i) }}">{{$i}}</a>
 </li>
 @endfor
 @if($end < $country_data->lastPage())
 @if($country_data->currentPage() + 3 != $country_data->lastPage())
 <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
 @endif
 <li class="page-item">
   <a class="page-link" href="{{ $country_data->url($country_data->lastPage()) }}">{{$country_data->lastPage()}}</a>
 </li>
 @endif
 @if ($country_data->hasMorePages())
 <li class="page-item">
   <a class="page-link" href="{{ $country_data->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
 </li>
 @else
 <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
   <span class="page-link" aria-hidden="true">Next</span>
 </li>
 @endif
</ul>
@endif
</div>
</div>
</div>
</td>
</tr>