@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Load time') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>  {{ __('The webpage loaded in :value seconds.', ['value' => number_format($summaryAuditPages['results'][$name]['value'], 2, __('.'), __(','))]) }}</p>
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'too_slow')
                               <p> {{ __('The webpage load should be under :value seconds.', ['value' => number_format($details['max'], 2, __('.'), __(','))]) }} </p>
                                <p><small>{{ __('The webpage loaded in :value seconds.', ['value' => number_format($summaryAuditPages['results'][$name]['value'], 2, __('.'), __(','))]) }}</small></p>
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
			<p>{{ __('The load time indicates the HTML\'s total load time, and does not include the external resources, such as images, scripts, or other resources.') }}</p>
			
		</div>
	</div>
</li>
@endif