<tr><td colspan="5">
  <div class="project-table-cover">
    <div class="project-table-foot" id="queries-foot">
      <div class="project-entries">
        <p>Showing {{$query_data->firstItem() }} to {{ $query_data->lastItem() }} of {{ $query_data->total() }} entries</p>
      </div>
     <div class="pagination vk-queries-pagination">
       @if ($query_data->hasPages())
       <ul class="pagination" role="navigation">
        @if ($query_data->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
         <span class="page-link" aria-hidden="true">Previous</span>
       </li>
       @else
       <li class="page-item">
         <a class="page-link" href="{{ $query_data->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
       </li>
       @endif
       <?php
       $start = $query_data->currentPage() - 2; 
       $end = $query_data->currentPage() + 2; 
       if($start < 1) {
         $start = 1; 
         $end += 1;
       } 
       if($end >= $query_data->lastPage() ) $end = $query_data->lastPage(); 
       ?>
       @if($start > 1)
       <li class="page-item">
         <a class="page-link" href="{{ $query_data->url(1) }}">{{1}}</a>
       </li>
       @if($query_data->currentPage() != 4)
       <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
       @endif
       @endif
       @for ($i = $start; $i <= $end; $i++)
       <li class="page-item {{ ($query_data->currentPage() == $i) ? ' active' : '' }}">
         <a class="page-link" href="{{ $query_data->url($i) }}">{{$i}}</a>
       </li>
       @endfor
       @if($end < $query_data->lastPage())
       @if($query_data->currentPage() + 3 != $query_data->lastPage())
       <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
       @endif
       <li class="page-item">
         <a class="page-link" href="{{ $query_data->url($query_data->lastPage()) }}">{{$query_data->lastPage()}}</a>
       </li>
       @endif
       @if ($query_data->hasMorePages())
       <li class="page-item">
         <a class="page-link" href="{{ $query_data->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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