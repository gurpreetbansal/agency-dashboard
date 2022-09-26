@if(isset($summaryAuditPages['results'][$name]))
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
</li>
@endif