@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Text compression') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The HTML file is compressed.') }}</p>
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'missing')
                               <p> {{ __('The HTML file is not compressed.') }} </p>
                        @endif
        		@endforeach
        	@endif
                <p><small>{{ __('The HTML filesize is :value.', ['value' => formatBytes($summaryAuditPages['results'][$name]['value'], 2, __('.'), __(','))]) }}</small></p>
        	
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p>{{ __('The 404 webpage status inform the users and the search engines that a page is missing.') }}</p>
			<hr>
			<p>Learn more 
                                <a href="https://www.gnu.org/software/gzip/" target="_blank" rel="nofollow" >Gzip <span uk-icon="arrow-right"></span></a>
                                <a href="https://brotli.org/" target="_blank" rel="nofollow" >Brotli <span uk-icon="arrow-right"></span></a>
                                <a href="https://www.bing.com/webmasters/help/404pages-best-practices-1c9f53b3" target="_blank" rel="nofollow" >Google <span uk-icon="arrow-right"></span></a>
                        </p>
		</div>
	</div>
</li>
@endif