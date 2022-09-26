<div class="white-box-head">
	<h5>Overview</h5>
	<p uk-tooltip="title: 2022-03-16 07:10:00; pos: top-center">{{ $summaryAuditPages->updated_at->diffForHumans() }}</p>
</div>
<div class="white-box-body">
	<div class="left">
		<div class="circle-donut" style="width:208px;height:208px;">
			<div class="circle_inbox"><span class="percent_text">{{ $summaryAuditPages->result }}</span> of 100</div>
			<input type="hidden" class="audit-pages-value" value="{{ $summaryAuditPages->result }}">
			<canvas id="audit-pages" width="208" height="208" style="display: block; width: 208px; height: 208px;" class="chartjs-render-monitor"></canvas>
		</div>
	</div>
	<div class="right">
		<div class="score-for page2">
			<h1>{{ $summaryAuditPages->project }}</h1>
			<h5>{{ $summaryAuditPages['results']['title']['value'] }}</h5>
			<p>{{ $summaryAuditPages['results']['meta_description']['value'] }}</p>
			<p><a href="{{ $summaryAuditPages->url }}" rel="nofollow" target="_blank">{{ $summaryAuditPages->url }}</a></p>
		</div>
	</div>
</div>
<div class="white-box-foot">
	<div class="uk-child-width-expand@s uk-grid">
	    <div>
	        <div class="issuesSingle">
	        	<div class="uk-flex">
	        		<div class="issueName">
	        			<svg xmlns="http://www.w3.org/2000/svg" class="text-danger mr-2" viewBox="0 0 19.06 17.01"><path d="M2,17H17.06a2,2,0,0,0,1.73-3L11.26,1A2,2,0,0,0,7.8,1L.27,14A2,2,0,0,0,2,17Z"></path></svg>
	        			<p>{{ $summaryAuditPages->highIssuesCount <= 1 ? $summaryAuditPages->highIssuesCount .' High issue': $summaryAuditPages->highIssuesCount .' High issues' }} </p>
	        		</div>
	        		<p>{{ number_format((($summaryAuditPages->highIssuesCount / $summaryAuditPages->totalTestsCount) * 100), 1, __('.'), __(',')) }}%</p>
	        	</div>
	        	<progress class="uk-progress bg-danger" value="{{ number_format((($summaryAuditPages->highIssuesCount / $summaryAuditPages->totalTestsCount) * 100)) }}" max="100"></progress>
	        </div>
	    </div>
	    <div>
	        <div class="issuesSingle">
	        	<div class="uk-flex">
	        		<div class="issueName">
	        			<svg xmlns="http://www.w3.org/2000/svg" class="text-warning mr-2" viewBox="0 0 18 18"><path d="M0,3.6V14.4A3.61,3.61,0,0,0,3.6,18H14.4A3.61,3.61,0,0,0,18,14.4V3.6A3.61,3.61,0,0,0,14.4,0H3.6A3.61,3.61,0,0,0,0,3.6Z"></path></svg>
	        			<p>{{ $summaryAuditPages->mediumIssuesCount <= 1 ? $summaryAuditPages->mediumIssuesCount .' medium issue': $summaryAuditPages->mediumIssuesCount .' medium issues' }}</p>
	        		</div>
	        		<p>{{ number_format((($summaryAuditPages->mediumIssuesCount / $summaryAuditPages->totalTestsCount) * 100), 1, __('.'), __(',')) }}%</p>
	        	</div>
	        	<progress class="uk-progress bg-warning" value="{{ number_format((($summaryAuditPages->mediumIssuesCount / $summaryAuditPages->totalTestsCount) * 100)) }}" max="100"></progress>
	        </div>
	    </div>
	    <div>
	        <div class="issuesSingle">
	        	<div class="uk-flex">
	        		<div class="issueName">
	        			<svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mr-2" viewBox="0 0 20 20"><path d="M10,0A10,10,0,1,0,20,10,10,10,0,0,0,10,0Z"></path></svg>
	        			<p>{{ $summaryAuditPages->lowIssuesCount <= 1 ? $summaryAuditPages->lowIssuesCount .' low issue': $summaryAuditPages->lowIssuesCount .' low issues' }}</p>
	        		</div>
	        		<p>{{ number_format((($summaryAuditPages->lowIssuesCount / $summaryAuditPages->totalTestsCount) * 100), 1, __('.'), __(',')) }}%</p>
	        	</div>
	        	<progress class="uk-progress bg-secondary" value="{{ number_format((($summaryAuditPages->lowIssuesCount / $summaryAuditPages->totalTestsCount) * 100)) }}" max="100"></progress>
	        </div>
	    </div>
	    <div>
	        <div class="issuesSingle">
	        	<div class="uk-flex">
	        		<div class="issueName">
	        			<p>{{ $summaryAuditPages->nonIssuesCount <= 1 ? $summaryAuditPages->nonIssuesCount .' tests passed': $summaryAuditPages->nonIssuesCount .' tests passed' }}</p>
	        		</div>
	        		<p>{{ number_format((($summaryAuditPages->nonIssuesCount / $summaryAuditPages->totalTestsCount) * 100), 1, __('.'), __(',')) }}%</p>
	        	</div>
	        	<progress class="uk-progress bg-success" value="{{ number_format((($summaryAuditPages->nonIssuesCount / $summaryAuditPages->totalTestsCount) * 100)) }}" max="100"></progress>
	        </div>
	    </div>
	</div>
</div>
<div class="white-box-foot">
	<div class="uk-child-width-expand@s uk-grid">
	    <div>
	        <div class="dataSingle">
    			<svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-4 height-4" viewBox="0 0 18 21"><path d="M12,0H6V2h6ZM8,13h2V7H8Zm8-6.61L17.45,5A11,11,0,0,0,16,3.56L14.62,5A9,9,0,1,0,16,6.39ZM9,19a7,7,0,1,1,7-7A7,7,0,0,1,9,19Z"></path></svg>
        		<p uk-tooltip="title: Load time; pos: top-center">{{ number_format($summaryAuditPages['results']['load_time']['value'], 2, __('.'), __(',')) }} seconds</p>
	        </div>
	    </div>
	    <div>
	        <div class="dataSingle">
    			<svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-4 height-4" viewBox="0 0 20 19"><path d="M11,5.83A3,3,0,0,0,12.83,4H16l-3,7a3.28,3.28,0,0,0,3.5,3A3.28,3.28,0,0,0,20,11L17,4h2V2H12.83A3,3,0,0,0,7.17,2H1V4H3L0,11a3.28,3.28,0,0,0,3.5,3A3.28,3.28,0,0,0,7,11L4,4H7.17A3,3,0,0,0,9,5.83V17H0v2H20V17H11ZM18.37,11H14.63L16.5,6.64Zm-13,0H1.63L3.5,6.64ZM10,4a1,1,0,1,1,1-1A1,1,0,0,1,10,4Z"></path></svg>
        		<p uk-tooltip="title: Page size; pos: top-center">{{ formatBytes($summaryAuditPages['results']['page_size']['value'], 2, __('.'), __(',')) }}</p>
	        </div>
	    </div>
	    <div>
	        <div class="dataSingle">
    			<svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-4 height-4" viewBox="0 0 18 18"><path d="M14,13,10,9V5.82a3,3,0,1,0-2,0V9L4,13H0v5H5V15l4-4.2L13,15V18h5V13Z"></path></svg>
        		<p uk-tooltip="title: HTTP requests; pos: top-center">{{ __((array_sum(array_map('count', $summaryAuditPages['results']['http_requests']['value'])) == 1 ? ':value resource' : ':value resources'), ['value' => number_format(array_sum(array_map('count', $summaryAuditPages['results']['http_requests']['value'])), 0, __('.'), __(','))]) }}</p>
	        </div>
	    </div>
	    <div>
	        <div class="dataSingle">
    			<svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-4 height-4" viewBox="0 0 16 21"><path d="M14,7H13V5A5,5,0,0,0,3,5V7H2A2,2,0,0,0,0,9V19a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V9A2,2,0,0,0,14,7ZM5,5a3,3,0,0,1,6,0V7H5Zm9,14H2V9H14ZM8,16a2,2,0,1,0-2-2A2,2,0,0,0,8,16Z"></path></svg>
    			<p uk-tooltip="title: HTTPS encryption; pos: top-center"> 
    				@if($summaryAuditPages['results']['https_encryption']['passed'])
                        {{ __('Secure') }}
                    @else
                        {{ __('Insecure') }}
                    @endif
                </p>
	        </div>
	    </div>
	</div>
</div>
