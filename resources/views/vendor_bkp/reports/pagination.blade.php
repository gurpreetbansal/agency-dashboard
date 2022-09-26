@if(isset($result) && !empty($result) && ($result->total() > 0))
<div class="project-entries ajax-loader">
   <p>Showing {{$result->firstItem() }} to {{ $result->lastItem() }} of {{ $result->total() }} entries</p>
</div>
<div class="pagination schedulereport ajax-loader">
   @if ($result->hasPages())
   <ul class="pagination" role="navigation">
      @if ($result->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $result->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $result->currentPage() - 2; // show 3 pagination links before current
         $end = $result->currentPage() + 2; // show 3 pagination links after current
         if($start < 1) {
             $start = 1; // reset start to 1
             $end += 1;
          } 
         if($end >= $result->lastPage() ) $end = $result->lastPage(); // reset end to last page
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $result->url(1) }}">{{1}}</a>
      </li>
      @if($result->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($result->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $result->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $result->lastPage())
      @if($result->currentPage() + 3 != $result->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $result->url($result->lastPage()) }}">{{$result->lastPage()}}</a>
      </li>
      @endif
      @if ($result->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $result->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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