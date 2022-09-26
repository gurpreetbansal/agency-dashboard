@if(isset($summaryAuditPages['results'][$name]))
<li>
        <div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('DOM size') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The DOM size is optimal.') }}</p>
                        <p><small>{{ __('The HTML file has :value DOM nodes.', ['value' => $summaryAuditPages['results'][$name]['value']]) }}</small></p>
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'too_many')
                               <p> {{ __('There are more than :value DOM nodes.', ['value' => number_format($details['max'], 0, __('.'), __(','))]) }} </p>
                        @endif
        		@endforeach
        	@endif
            	
        </div>
    </div>
</li>
@endif