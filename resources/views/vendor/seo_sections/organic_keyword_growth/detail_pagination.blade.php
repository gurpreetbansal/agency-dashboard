@if(isset($keywords) && !empty($keywords) && count($keywords) > 0)
<div class="project-entries">
	<p>Showing {{$keywords->firstItem() }} to {{ $keywords->lastItem() }} of {{ $keywords->total() }} entries</p>
</div>
<div class="pagination OrgnicDetail">
	@if ($keywords->hasPages())
	<ul class="pagination" role="navigation">
		@if ($keywords->onFirstPage())
		<li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
			<span class="page-link" aria-hidden="true">Previous</span>
		</li>
		@else
		<li class="page-item">
			<a class="page-link" href="{{ $keywords->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
		</li>
		@endif


		<?php
			        $start = $keywords->currentPage() - 2; // show 3 pagination links before current
			        $end = $keywords->currentPage() + 2; // show 3 pagination links after current
			        if($start < 1) {
			            $start = 1; // reset start to 1
			            $end += 1;
			        } 
			        if($end >= $keywords->lastPage() ) $end = $keywords->lastPage(); // reset end to last page
			        ?>


			        @if($start > 1)
			        <li class="page-item">
			        	<a class="page-link" href="{{ $keywords->url(1) }}">{{1}}</a>
			        </li>
			        @if($keywords->currentPage() != 4)
			        <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
			        @endif
			        @endif
			        @for ($i = $start; $i <= $end; $i++)
			        <li class="page-item {{ ($keywords->currentPage() == $i) ? ' active' : '' }}">
			        	<a class="page-link" href="{{ $keywords->url($i) }}">{{$i}}</a>
			        </li>
			        @endfor
			        @if($end < $keywords->lastPage())
			        @if($keywords->currentPage() + 3 != $keywords->lastPage())
			        <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
			        @endif
			        <li class="page-item">
			        	<a class="page-link" href="{{ $keywords->url($keywords->lastPage()) }}">{{$keywords->lastPage()}}</a>
			        </li>
			        @endif

			        @if ($keywords->hasMorePages())
			        <li class="page-item">
			        	<a class="page-link" href="{{ $keywords->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
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