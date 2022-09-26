@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('404 page') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The website has 404 error pages.') }}</p>
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'missing')
                                <p> {{ __('The website does not have 404 error pages.') }} </p>
                        @endif
        		@endforeach
        	@endif
        	<p><small><a target="_blank" href="{{ $summaryAuditPages['results'][$name]['value'] }}">{{ $summaryAuditPages['results'][$name]['value'] }}</a></small></p>
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p>{{ __('The 404 webpage status inform the users and the search engines that a page is missing.') }}</p>
			<hr>
			<p>Learn more <a target="_blank" href="https://www.bing.com/webmasters/help/404pages-best-practices-1c9f53b3">Google <span uk-icon="arrow-right"></span></a></p>
		</div>
	</div>
</li>
@endif