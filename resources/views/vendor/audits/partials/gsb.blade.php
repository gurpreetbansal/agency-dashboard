
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
                <h5>
            	@include('vendor.audits.partials.status')
                {{ __('Google Safe Browsing') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>  {{ __('The webpage is in good standing.') }}</p>
                        
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		  @if($error == 'failed')
                               <p> {{ __('The webpage is not in good standing.') }} </p>
                        @endif
        		@endforeach
        	@endif
            	
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p> {{ __('The Google Safe Browsing indicates the webpage status for malware and phishing content.') }}</p>
			<hr>
			<p>Learn more 
                                <a target="_blank" href="https://developers.google.com/search/docs/advanced/security/malware?hl=en&ref_topic=4596795" target="_blank" rel="nofollow" >Google <span uk-icon="arrow-right"></span></a>
                        </p>
		</div>
	</div>
</li>
