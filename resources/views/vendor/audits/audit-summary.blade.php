<input type="hidden" class="audit-id" value="{{ $summaryAudit->id }}">
<div class="white-box-body">
	<div class="left">
		<div class="circle-donut" style="width:208px;height:208px;">
			<div class="circle_inbox"><span class="percent_text">{{ $summaryAudit->result }}</span> of 100</div>
			<input type="hidden" class="audit-overview-value" value="{{ $summaryAudit->result }}">
			<canvas id="audit-overview" width="208" height="208" style="display: block; width: 208px; height: 208px;" class="chartjs-render-monitor"></canvas>
		</div>
	</div>
	<div class="right uk-flex">
		<div class="score-for">
			<p><small>Website score for</small></p>
			<h2>{{ $summaryAudit->project }}</h2>
			<p><a data-pd-popup-open="howisitcalculated">How is it calculated?</a></p>
			<ul>
				<li><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $summaryAudit->ip }}</li>
				<li>|</li>
				<li><i class="fa fa-lock" aria-hidden="true"></i>  {{ $summaryAudit->ip <> null ? 'enabled' :'not enabled' }}</li>
			</ul>

			@if($auditType == 'individual')
			<span class="audit-disable" {{ $summaryAudit->audit_status == 'process' ? 'style=cursor:not-allowed' : '' }} >
				<a target="_blank"  href="{{ @$pdf_url }}" class="btn btn-sm blue-btn summary-pdf-download" {{ $summaryAudit->audit_status == 'process' ? 'style=pointer-events:none' : '' }} >Download PDF</a>
			</span>
			@endif
		</div>
		<div class="elem-end">
			<article>
				<div class="progress-loader" {{ $summaryAudit->audit_status == 'process' ? 'style=display:block' : '' }}></div>
				<ul>
					<li>
						<div>Crawled pages</div>
						<div class="crawled-pages">
							<span id="crawled-pages"> {{ count($summaryAudit->pages) }}</span>/ @if(Auth::user() <> null && Auth::user()->user_type == 0)@endif
							@if($auditType == 'individual-audit')
							<span> {{ $summaryAudit->crowl_pages }}
							@else
							@if(Auth::user() <> null && Auth::user()->user_type == 0)
							<select id="audit-limit-change" class="selectpicker limit-change" {{ $summaryAudit->audit_status == 'process' ? 'disabled' : '' }}>
								<option value="50" {{ $summaryAudit->crowl_pages == 50 ? 'selected=""':'' }} >50</option>
								<option value="100" {{ $summaryAudit->crowl_pages == 100 ? 'selected=""':'' }} >100</option>
								<option value="500" {{ $summaryAudit->crowl_pages == 500 ? 'selected=""':'' }} >500</option>
							</select>
							@else
							<span> {{ $summaryAudit->crowl_pages }}
							@endif

							@endif
						</div>
					</li>
					<li>
						<div>Latest Crawl</div>
						<div>{{ date('M d Y',strtotime($summaryAudit->updated_at)) }}</div>
					</li>
				</ul>
				<ul>
					<li>
						<div>
							<img src="{{ url('/public/vendor/internal-pages/images/google-indexed-logo.png') }}">
							Google indexed pages
						</div>
						<div id="noindex-pages">{{ $summaryAudit->noindex }}</div>
					</li>
					<li>
						@if($summaryAudit->is_ssl == 1)
						<div>
							<img src="{{ url('/public/vendor/internal-pages/images/google-safe-icon.png') }}">
							Google safe browsing
						</div>
						<div>
							Site is safe
						</div>
						@else
						<div>
							<img src="{{ url('/public/vendor/internal-pages/images/google-safe-browsing-logo.png') }}">
							Google safe browsing
						</div>
						<div>
							Site is not safe
						</div>
						@endif
					</li>
				</ul>
			</article>
		</div>
	</div>
</div>
<div class="white-box-foot">
	<div class="uk-child-width-expand@s uk-grid">
		<div>
			<div class="issuesSingle">
				<div class="uk-flex">
					<div class="issueName criticals">
						<svg xmlns="http://www.w3.org/2000/svg" class="text-danger mr-2" viewBox="0 0 19.06 17.01"><path d="M2,17H17.06a2,2,0,0,0,1.73-3L11.26,1A2,2,0,0,0,7.8,1L.27,14A2,2,0,0,0,2,17Z"></path></svg>
						<p>{{ $summaryAudit->criticals }} high issues</p>
					</div>
					<p id="criticalsAvg" >{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->criticals / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
				</div>
				<progress class="uk-progress criticalsProgress bg-danger" value="{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->criticals / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			</div>
		</div>
		<div>
			<div class="issuesSingle">
				<div class="uk-flex">
					<div class="issueName warnings">
						<svg xmlns="http://www.w3.org/2000/svg" class="text-warning mr-2" viewBox="0 0 18 18"><path d="M0,3.6V14.4A3.61,3.61,0,0,0,3.6,18H14.4A3.61,3.61,0,0,0,18,14.4V3.6A3.61,3.61,0,0,0,14.4,0H3.6A3.61,3.61,0,0,0,0,3.6Z"></path></svg>
						<p>{{ $summaryAudit->warnings }} medium issues</p>
					</div>
					<p id="warningsAvg" >{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->warnings / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
				</div>

				<progress class="uk-progress warningsProgress bg-warning" value="{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->warnings / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			</div>
		</div>
		<div>
			<div class="issuesSingle">
				<div class="uk-flex">
					<div class="issueName notices">
						<svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mr-2" viewBox="0 0 20 20"><path d="M10,0A10,10,0,1,0,20,10,10,10,0,0,0,10,0Z"></path></svg>
						<p>{{ $summaryAudit->notices }} low issues</p>
					</div>
					<p id="noticesAvg" >{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->notices / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
				</div>
				<progress class="uk-progress noticesProgress bg-secondary" value="{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->notices / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			</div>
		</div>
		<div>
			<div class="issuesSingle">
				<div class="uk-flex">
					<div class="issueName passed">
						<p>{{ $summaryAudit->total_tests - ($summaryAudit->criticals + $summaryAudit->warnings + $summaryAudit->notices ) }} tests passed</p>
					</div>
					<p id="passedAvg">{{ $summaryAudit->total_tests > 0 ? number_format(((($summaryAudit->total_tests - ($summaryAudit->criticals + $summaryAudit->warnings + $summaryAudit->notices )) / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
				</div>
				<progress class="uk-progress passedProgress bg-success" value="{{ $summaryAudit->total_tests > 0 ? number_format(((($summaryAudit->total_tests - ($summaryAudit->criticals + $summaryAudit->warnings + $summaryAudit->notices )) / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			</div>
		</div>
	</div>
</div>