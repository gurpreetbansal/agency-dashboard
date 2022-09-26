@if($backlink_records->total() > 0)
<div class="project-entries">
   <p>Showing {{$backlink_records->firstItem() }} to {{ $backlink_records->lastItem() }} of {{ $backlink_records->total() }} entries</p>
</div>
<div class="pagination  Backlinks">
   @if ($backlink_records->hasPages())
   <ul class="pagination" role="navigation">
      @if ($backlink_records->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $backlink_records->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $backlink_records->currentPage() - 2; // show 3 pagination links before current
         $end = $backlink_records->currentPage() + 2; // show 3 pagination links after current
         if($start < 1) {
             $start = 1; // reset start to 1
             $end += 1;
          } 
         if($end >= $backlink_records->lastPage() ) $end = $backlink_records->lastPage(); // reset end to last page
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $backlink_records->url(1) }}">{{1}}</a>
      </li>
      @if($backlink_records->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($backlink_records->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $backlink_records->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $backlink_records->lastPage())
      @if($backlink_records->currentPage() + 3 != $backlink_records->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $backlink_records->url($backlink_records->lastPage()) }}">{{$backlink_records->lastPage()}}</a>
      </li>
      @endif
      @if ($backlink_records->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $backlink_records->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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