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
</li>
@endif