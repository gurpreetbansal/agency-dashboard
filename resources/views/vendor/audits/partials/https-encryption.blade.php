@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('HTTPS encryption') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p> {{ __('The webpage uses HTTPS encryption.') }}</p>
                        
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'missing')
                               <p> {{ __('The webpage does not use HTTPS encryption.') }} </p>
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
			<p> {{ __('The HTTPS encryption helps protecting the user\'s security and privacy.') }}</p>
			<hr>
			<p>Learn more 
                                <a href="https://developers.google.com/search/docs/advanced/security/https" target="_blank" rel="nofollow" >Google <span uk-icon="arrow-right"></span></a>
                        </p>
		</div>
	</div>
</li>
@endif