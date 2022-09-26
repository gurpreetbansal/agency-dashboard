@if(isset($alerts) && !empty($alerts) && ($alerts->total() > 0))
  <div class="project-entries ajax-loader negative-alerts-text">
   <p>Showing {{$alerts->firstItem() }} to {{ $alerts->lastItem() }} of {{ $alerts->total() }} entries</p>
  </div>
  <div class="pagination ajax-loader NegativeAlerts">
    @if ($alerts->hasPages())
   <ul class="pagination">
      @if ($alerts->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $alerts->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $alerts->currentPage() - 2; // show 3 pagination links before current
         $end = $alerts->currentPage() + 2; // show 3 pagination links after current
         if($start < 1) {
             $start = 1; // reset start to 1
             $end += 1;
          } 
         if($end >= $alerts->lastPage() ) $end = $alerts->lastPage(); // reset end to last page
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $alerts->url(1) }}">{{1}}</a>
      </li>
      @if($alerts->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($alerts->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $alerts->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $alerts->lastPage())
      @if($alerts->currentPage() + 3 != $alerts->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $alerts->url($alerts->lastPage()) }}">{{$alerts->lastPage()}}</a>
      </li>
      @endif
      @if ($alerts->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $alerts->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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