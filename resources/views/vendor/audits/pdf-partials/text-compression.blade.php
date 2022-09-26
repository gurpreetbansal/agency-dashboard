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
</li>
@endif