@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Text to HTML ratio') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The text to HTML ratio is :value%.', ['value' => $summaryAuditPages['results'][$name]['value']]) }}</p>
                        
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		  @if($error == 'too_small')
                               <p> {{ __('The text to HTML ratio is under :min%.', ['min' => $details['min']]) }} </p>
                               <p><small>{{ __('The text to HTML ratio is :value%.', ['value' => $summaryAuditPages['results'][$name]['value']]) }}</small></p>
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
			<p>  {{ __('The text to HTML ratio represents the percentage of actual text compared to the percentage of HTML code on the webpage.') }} </p>
			
		</div>
	</div>
</li>
@endif