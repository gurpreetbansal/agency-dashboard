@if($campaign_data->total() > 0)
<div class="project-entries ajax-loader">
   <p>Showing {{$campaign_data->firstItem() }} to {{ $campaign_data->lastItem() }} of {{ $campaign_data->total() }} entries</p>
</div>
<div class="pagination ajax-loader">
   @if ($campaign_data->hasPages())
   <ul class="pagination" role="navigation">
      @if ($campaign_data->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $campaign_data->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $campaign_data->currentPage() - 2; // show 3 pagination links before current
         $end = $campaign_data->currentPage() + 2; // show 3 pagination links after current
         if($start < 1) {
             $start = 1; // reset start to 1
             $end += 1;
          } 
         if($end >= $campaign_data->lastPage() ) $end = $campaign_data->lastPage(); // reset end to last page
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $campaign_data->url(1) }}">{{1}}</a>
      </li>
      @if($campaign_data->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($campaign_data->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $campaign_data->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $campaign_data->lastPage())
      @if($campaign_data->currentPage() + 3 != $campaign_data->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $campaign_data->url($campaign_data->lastPage()) }}">{{$campaign_data->lastPage()}}</a>
      </li>
      @endif
      @if ($campaign_data->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $campaign_data->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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