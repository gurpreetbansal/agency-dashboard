@extends('layouts.pdf_layout')
@section('content')
<div class="sAudit-section pdf-download">
	<div class="white-box overviewBox">
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
	                <ul>
	                    <li><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $summaryAudit->ip }}</li>
	                    <li>|</li>
	                    <li><i class="fa fa-lock" aria-hidden="true"></i>  {{ $summaryAudit->ip <> null ? 'enabled' :'not enabled' }}</li>
	                </ul>
	            </div>
	            <div class="elem-end">
		            <article>
		                <ul>
		                    <li>
		                        <div>
		                        	<img src="https://waveitdigital.com/public/vendor/internal-pages/images/pages-icon.png" alt="pages-icon">
		                    		Crawled pages
		                    	</div>
		                        <div>{{ count($summaryAudit->pages) }}</div>
		                    </li>
                        </ul>
		                <ul>
		                    <li>
		                        <div>
		                        	<img src="https://imark.waveitdigital.com/public/vendor/internal-pages/images/google-indexed-logo.png">
		                        	Google indexed pages
		                        </div>
                                <div>{{ $summaryAudit->noindex }}</div>
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
			<div class="uk-child-width-1-4@s uk-grid">
			    <div>
			        <div class="issuesSingle">
			        	<div class="uk-flex">
			        		<div class="issueName">
			        			<svg xmlns="http://www.w3.org/2000/svg" class="text-danger mr-2" viewBox="0 0 19.06 17.01"><path d="M2,17H17.06a2,2,0,0,0,1.73-3L11.26,1A2,2,0,0,0,7.8,1L.27,14A2,2,0,0,0,2,17Z"></path></svg>
			        			<p>{{ $summaryAudit->criticals }} high issues</p>
			        		</div>
			        		<p>{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->criticals / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
			        	</div>
			        	<progress class="uk-progress bg-danger" value="{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->criticals / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			        </div>
			    </div>
			    <div>
			        <div class="issuesSingle">
			        	<div class="uk-flex">
			        		<div class="issueName">
			        			<svg xmlns="http://www.w3.org/2000/svg" class="text-warning mr-2" viewBox="0 0 18 18"><path d="M0,3.6V14.4A3.61,3.61,0,0,0,3.6,18H14.4A3.61,3.61,0,0,0,18,14.4V3.6A3.61,3.61,0,0,0,14.4,0H3.6A3.61,3.61,0,0,0,0,3.6Z"></path></svg>
			        			<p>{{ $summaryAudit->warnings }} medium issues</p>
			        		</div>
			        		<p>{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->warnings / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
			        	</div>
			        	<progress class="uk-progress bg-warning" value="{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->warnings / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			        </div>
			    </div>
			    <div>
			        <div class="issuesSingle">
			        	<div class="uk-flex">
			        		<div class="issueName">
			        			<svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mr-2" viewBox="0 0 20 20"><path d="M10,0A10,10,0,1,0,20,10,10,10,0,0,0,10,0Z"></path></svg>
			        			<p>{{ $summaryAudit->notices }} low issues</p>
			        		</div>
			        		<p>{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->notices / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
			        	</div>
			        	<progress class="uk-progress bg-secondary" value="{{ $summaryAudit->total_tests > 0 ? number_format((($summaryAudit->notices / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			        </div>
			    </div>
			    <div>
			        <div class="issuesSingle">
			        	<div class="uk-flex">
			        		<div class="issueName">
			        			<p>{{ $summaryAudit->total_tests - ($summaryAudit->criticals + $summaryAudit->warnings + $summaryAudit->notices ) }} tests passed</p>
			        		</div>
			        		<p>{{ $summaryAudit->total_tests > 0 ? number_format(((($summaryAudit->total_tests - ($summaryAudit->criticals + $summaryAudit->warnings + $summaryAudit->notices )) / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}%</p>
			        	</div>
			        	<progress class="uk-progress bg-success" value="{{ $summaryAudit->total_tests > 0 ? number_format(((($summaryAudit->total_tests - ($summaryAudit->criticals + $summaryAudit->warnings + $summaryAudit->notices )) / $summaryAudit->total_tests) * 100), 1, __('.'), __(',')) : 0 }}" max="100"></progress>
			        </div>
			    </div>
			</div>
		</div>
	</div>
	
	<div class="white-box pagesBox">
		<div class="white-box-head">
			<h5>Pages</h5>
		</div>
		<div class="white-box-body">
			<div class="auditTable">
                <table>
                	<thead>
                		<tr>
                			<th>URL</th>
                			<th>Result</th>
                			<th>Generated at</th>
                		</tr>
                	</thead>
                    <tbody>
                    	@foreach($summaryAudit->pages as $key => $value)
                    	<tr>
                    		<td>
                    			<div class="link-flex">
                    				<a href="{{ $value->url }}" uk-tooltip="title: cbdmovers.com.au/aboutus/; pos: top-center">{{ $value->url }}</a>
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
                    				</div>
                    			</div>
                    		</td>
                    		<td><span uk-tooltip="title: 2022-03-16 07:10:00; pos: top-center">{{ $value->updated_at->diffForHumans() }}</span> </td>
                		</tr>
                		@endforeach
                    </tbody>
                </table>
            </div>
		</div>
	</div>
</div>
@endsection