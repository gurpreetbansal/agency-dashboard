<div class="auditTable" id="auditTable">
	<table>
		<thead>
			<tr>
				<th>URL</th>
				<th>Result</th>
				<th>Generated at</th>
				@if($auditType !== 'individual-audit')
				<th></th>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach($summaryAuditPages as $key => $value)
			<tr>
				<td>
					<div class="link-flex">
						<a href="{{ $value->url }}" target="_blank" class="ext-link"></a>
						<a class="{{ $auditType == 'individual' || $auditType == 'individual-audit' ? 'audit-pages-details' : '' }}" data-audit="{{ $value->id }}" href="{{ $auditType == 'individual' || $auditType == 'individual-audit' ? url('/audit/page/detail').'/'.$value->summary->share_key.'/'.$value->id : url('/audit/page/detail').'/'.$value->id }}" uk-tooltip="title: {{ $value->url }}; pos: top-center">{{ $value->url }}</a>
					</div>
				</td>
				<td>
					<div class="progress-flex">
						@if($value->result > 79)
						<progress class="uk-progress bg-success" value="{{ $value->result }}" max="100"></progress>
						@elseif($value->result > 49)
						<progress class="uk-progress bg-warning" value="{{ $value->result }}" max="100"></progress>
						@else
						<progress class="uk-progress bg-danger" value="{{ $value->result }}" max="100"></progress>
						@endif
						
						<p><strong>{{ $value->result }}</strong>/100</p>
						<div class="badge-result">
							@if($value->result > 79)
							<a href="javascript:void(0)" class="badge-success">{{ __('Good') }}</a>
							@elseif($value->result > 49)
							<a href="javascript:void(0)" class="badge-warning">{{ __('Decent') }}</a>
							@else
							<a href="javascript:void(0)" class="badge-danger">{{ __('Bad') }}</a>
							@endif

							<div class="badge-tooltip">
								<ul>
									<li>
										<svg xmlns="http://www.w3.org/2000/svg" class="text-danger mr-2" viewBox="0 0 19.06 17.01"><path d="M2,17H17.06a2,2,0,0,0,1.73-3L11.26,1A2,2,0,0,0,7.8,1L.27,14A2,2,0,0,0,2,17Z"></path></svg>
										<p>{{ __(($value->highIssuesCount == 1 ? ':value high issue' : ':value high issues'), ['value' => number_format($value->highIssuesCount, 0, __('.'), __(','))]) }}</p>
									</li>
									<li>
										<svg xmlns="http://www.w3.org/2000/svg" class="text-warning mr-2" viewBox="0 0 18 18"><path d="M0,3.6V14.4A3.61,3.61,0,0,0,3.6,18H14.4A3.61,3.61,0,0,0,18,14.4V3.6A3.61,3.61,0,0,0,14.4,0H3.6A3.61,3.61,0,0,0,0,3.6Z"></path></svg>
										<p>{{ __(($value->mediumIssuesCount == 1 ? ':value medium issue' : ':value medium issues'), ['value' => number_format($value->mediumIssuesCount, 0, __('.'), __(','))]) }}</p>
									</li>
									<li>
										<svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mr-2" viewBox="0 0 20 20"><path d="M10,0A10,10,0,1,0,20,10,10,10,0,0,0,10,0Z"></path></svg>
										<p>{{ __(($value->lowIssuesCount == 1 ? ':value low issue' : ':value low issues'), ['value' => number_format($value->lowIssuesCount, 0, __('.'), __(','))]) }}</p>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</td>
				<td><span uk-tooltip="title: {{ $value->updated_at->diffForHumans() }}; pos: top-center">{{ $value->updated_at->diffForHumans() }}</span></td>
				@if($auditType !== 'individual-audit')
					<td>
						<div class="right-icons">
							<nav class="btn-group">
								<div class="uk-inline">
									<button class="btn icon-btn" type="button">
										<span uk-icon="more"></span>
									</button>
									<div uk-dropdown="mode: click">
										<nav>
											<a href="javascript:void(0)" class="individual-refresh" audit-id="{{ $value->id }}" ><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
											<a class="{{ $auditType == 'individual' ? 'audit-pages-details' : '' }}" data-audit="{{ $value->id }}" href="{{ url('/audit/page/detail').'/'.$value->id }}"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
										</nav>
									</div>
								</div>
							</nav>
						</div>
					</td>
				@endif
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="project-table-cover">
		<div class="project-table-foot" id="queries-foot">
			<div class="project-entries">
				<p>Showing {{$summaryAuditPages->firstItem() }} to {{ $summaryAuditPages->lastItem() }} of {{ $summaryAuditPages->total() }} entries</p>
			</div>
			<div class="pagination audit-pagination">
				@if ($summaryAuditPages->hasPages())
				<ul class="pagination" role="navigation">
					@if ($summaryAuditPages->onFirstPage())
					<li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
						<span class="page-audit" aria-hidden="true">Previous</span>
					</li>
					@else
					<li class="page-item">
						<a class="page-audit" href="{{ $summaryAuditPages->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
					</li>
					@endif
					<?php
			         $start = $summaryAuditPages->currentPage() - 2; // show 3 pagination links before current
			         $end = $summaryAuditPages->currentPage() + 2; // show 3 pagination links after current
			         if($start < 1) {
			             $start = 1; // reset start to 1
			             $end += 1;
			         } 
			         if($end >= $summaryAuditPages->lastPage() ) $end = $summaryAuditPages->lastPage(); // reset end to last page
			         ?>
			         @if($start > 1)
			         <li class="page-item">
			         	<a class="page-audit" href="{{ $summaryAuditPages->url(1) }}">{{1}}</a>
			         </li>
			         @if($summaryAuditPages->currentPage() != 4)
			         <li class="page-item disabled" aria-disabled="true"><span class="page-audit">...</span></li>
			         @endif
			         @endif
			         @for ($i = $start; $i <= $end; $i++)
			         <li class="page-item {{ ($summaryAuditPages->currentPage() == $i) ? ' active' : '' }}">
			         	<a class="page-audit" href="{{ $summaryAuditPages->url($i) }}">{{$i}}</a>
			         </li>
			         @endfor
			         @if($end < $summaryAuditPages->lastPage())
			         @if($summaryAuditPages->currentPage() + 3 != $summaryAuditPages->lastPage())
			         <li class="page-item disabled" aria-disabled="true"><span class="page-audit">...</span></li>
			         @endif
			         <li class="page-item">
			         	<a class="page-audit" href="{{ $summaryAuditPages->url($summaryAuditPages->lastPage()) }}">{{$summaryAuditPages->lastPage()}}</a>
			         </li>
			         @endif
			         @if ($summaryAuditPages->hasMorePages())
			         <li class="page-item">
			         	<a class="page-audit" href="{{ $summaryAuditPages->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
			         </li>
			         @else
			         <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
			         	<span class="page-audit" aria-hidden="true">Next</span>
			         </li>
			         @endif
			     </ul>
			     @endif
			</div>
		</div>
	</div>
</div>