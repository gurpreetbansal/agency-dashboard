@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Page size') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>  {{ __('The size of the HTML webpage is :value.', ['value' => formatBytes($summaryAuditPages['results'][$name]['value'], 2, __('.'), __(','))]) }} </p>
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'too_large')
                               <p> {{ __('The webpage HTML size is larger than :value.', ['value' => formatBytes($details['max'], 2, __('.'), __(','))]) }}</p>
                               
                        @endif
        		@endforeach
        	@endif
               
        	
        </div>
    </div>
   
</li>
@endif