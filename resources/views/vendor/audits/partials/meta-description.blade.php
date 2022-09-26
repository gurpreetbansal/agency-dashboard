@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
        		@include('vendor.audits.partials.status')
            	
            	{{ __('Meta description') }}
            </h5>
        </div>

         @if($summaryAuditPages['results'][$name]['passed'])

        <div class="uk-width-3-4 text-success">
        	<p>{{ __('The meta description tag is good.') }}</p>
        	<p><small>{{ $summaryAuditPages['results'][$name]['value'] }}s</small></p>
        </div>
        @else
        	 @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		<div class="uk-width-3-4 ">
                    @if($error == 'missing')
                        {{ __('The meta description tag is missing or empty.') }}
                    @endif
                </div>
        	@endforeach
        @endif

    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p> {{ __('The meta description is an HTML tag that provides a short and accurate summary of the webpage.') }} {{ __('The meta description is used by search engines to identify a webpage\'s topic and provide relevant search results.') }}</p>
			<hr>
			<p>Learn more <a href="https://developers.google.com/search/docs/advanced/appearance/snippet" target="_blank">Google <span uk-icon="arrow-right"></span></a></p>
		</div>
	</div>
</li>
@endif