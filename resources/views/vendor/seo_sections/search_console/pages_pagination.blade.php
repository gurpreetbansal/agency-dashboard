<tr><td colspan="5">
  <div class="project-table-cover">
  <div class="project-table-foot" id="pages-foot">

<div class="project-entries">
 <p>Showing {{$pages_data->firstItem() }} to {{ $pages_data->lastItem() }} of {{ $pages_data->total() }} entries</p>
</div>
<div class="pagination pages-pagination">
 @if ($pages_data->hasPages())
 <ul class="pagination" role="navigation">
  @if ($pages_data->onFirstPage())
  <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
   <span class="page-link" aria-hidden="true">Previous</span>
 </li>
 @else
 <li class="page-item">
   <a class="page-link" href="{{ $pages_data->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
 </li>
 @endif
 <?php
 $start = $pages_data->currentPage() - 2; 
 $end = $pages_data->currentPage() + 2; 
 if($start < 1) {
   $start = 1; 
   $end += 1;
 } 
 if($end >= $pages_data->lastPage() ) $end = $pages_data->lastPage(); 
 ?>
 @if($start > 1)
 <li class="page-item">
   <a class="page-link" href="{{ $pages_data->url(1) }}">{{1}}</a>
 </li>
 @if($pages_data->currentPage() != 4)
 <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
 @endif
 @endif
 @for ($i = $start; $i <= $end; $i++)
 <li class="page-item {{ ($pages_data->currentPage() == $i) ? ' active' : '' }}">
   <a class="page-link" href="{{ $pages_data->url($i) }}">{{$i}}</a>
 </li>
 @endfor
 @if($end < $pages_data->lastPage())
 @if($pages_data->currentPage() + 3 != $pages_data->lastPage())
 <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
 @endif
 <li class="page-item">
   <a class="page-link" href="{{ $pages_data->url($pages_data->lastPage()) }}">{{$pages_data->lastPage()}}</a>
 </li>
 @endif
 @if ($pages_data->hasMorePages())
 <li class="page-item">
   <a class="page-link" href="{{ $pages_data->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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