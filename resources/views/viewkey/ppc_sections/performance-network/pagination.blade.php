@if(isset($results) && !empty($results))
<div class="project-entries">
   <p>Showing {{$results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} entries</p>
</div>
<div class="pagination adsPerformanceNetworks">
   @if ($results->hasPages())
   <ul class="pagination" role="navigation">
      @if ($results->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $results->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $results->currentPage() - 2; 
         $end = $results->currentPage() + 2; 
         if($start < 1) {
             $start = 1; 
             $end += 1;
           } 
         if($end >= $results->lastPage() ) $end = $results->lastPage(); 
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $results->url(1) }}">{{1}}</a>
      </li>
      @if($results->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($results->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $results->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $results->lastPage())
      @if($results->currentPage() + 3 != $results->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $results->url($results->lastPage()) }}">{{$results->lastPage()}}</a>
      </li>
      @endif
      @if ($results->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $results->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
      </li>
      @else
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
         <span class="page-link" aria-hidden="true">Next</span>
      </li>
      @endif
   </ul>
   @endif
</div>
@endif