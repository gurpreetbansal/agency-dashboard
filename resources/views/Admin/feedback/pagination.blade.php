@if(isset($data) && !empty($data))
<div class="project-entries ajax-loader">
   <p>Showing {{$data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries</p>
</div>
<div class="pagination admin-feedback-list ajax-loader">
   @if ($data->hasPages())
   <ul class="pagination" role="navigation">
      @if ($data->onFirstPage())
      <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
      </li>
      @else
      <li class="page-item">
         <a class="page-link" href="{{ $data->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
      </li>
      @endif
      <?php
         $start = $data->currentPage() - 2; // show 3 pagination links before current
         $end = $data->currentPage() + 2; // show 3 pagination links after current
         if($start < 1) {
             $start = 1; // reset start to 1
             $end += 1;
          } 
         if($end >= $data->lastPage() ) $end = $data->lastPage(); // reset end to last page
         ?>
      @if($start > 1)
      <li class="page-item">
         <a class="page-link" href="{{ $data->url(1) }}">{{1}}</a>
      </li>
      @if($data->currentPage() != 4)
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      @endif
      @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ ($data->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $data->url($i) }}">{{$i}}</a>
      </li>
      @endfor
      @if($end < $data->lastPage())
      @if($data->currentPage() + 3 != $data->lastPage())
      <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
         <a class="page-link" href="{{ $data->url($data->lastPage()) }}">{{$data->lastPage()}}</a>
      </li>
      @endif
      @if ($data->hasMorePages())
      <li class="page-item">
         <a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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