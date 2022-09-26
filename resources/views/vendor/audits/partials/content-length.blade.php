@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Content length') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The webpage has :value words.', ['value' => number_format($summaryAuditPages['results'][$name]['value'], 0, __('.'), __(','))]) }}</p>
                        
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'too_few')
                               <p> {{ __('The are less than :value words on the webpage.', ['value' => number_format($details['min'], 0, __('.'), __(','))]) }} </p>
                               <p><small>{{ __('The webpage has :value words.', ['value' => number_format($summaryAuditPages['results'][$name]['value'], 0, __('.'), __(','))]) }}</small></p>
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
			<p> {{ __('The content length represents the number of words found on the webpage.') }} {{ __('The webpage should contain a decent amount of words.') }} </p>
			<hr>
			<p>Learn more 
                                <a href="https://developers.google.com/search/docs/beginner/seo-starter-guide#provide-an-appropriate-amount-of-content-for-your-subject" target="_blank" rel="nofollow" >Google <span uk-icon="arrow-right"></span></a>
                        </p>
		</div>
	</div>
</li>
@endif