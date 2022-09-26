@if(isset($live_keywords) && !empty($live_keywords))
<div class="project-entries ajax-loader">
   <p>Showing {{$live_keywords->firstItem() }} to {{ $live_keywords->lastItem() }} of {{ $live_keywords->total() }} entries</p>
</div>
<div class="pagination LiveKeywords ajax-loader">
   @if ($live_keywords->hasPages())
   <ul class="pagination" role="navigation">
      @if ($live_keywords->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $live_keywords->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $live_keywords->currentPage() - 2; // show 3 pagination links before current
         $end = $live_keywords->currentPage() + 2; // show 3 pagination links after current
         if($start < 1) {
             $start = 1; // reset start to 1
             $end += 1;
          } 
         if($end >= $live_keywords->lastPage() ) $end = $live_keywords->lastPage(); // reset end to last page
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $live_keywords->url(1) }}">{{1}}</a>
      </li>
      @if($live_keywords->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($live_keywords->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $live_keywords->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $live_keywords->lastPage())
      @if($live_keywords->currentPage() + 3 != $live_keywords->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $live_keywords->url($live_keywords->lastPage()) }}">{{$live_keywords->lastPage()}}</a>
      </li>
      @endif
      @if ($live_keywords->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $live_keywords->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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