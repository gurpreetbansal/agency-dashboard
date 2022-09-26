@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
        		@include('vendor.audits.partials.status')
            	
            	{{ __('Meta description') }}
            </h5>
        </div>

         @if($summaryAuditPages['results'][$name]['passed'])

        <div class="uk-width-3-4 text-success">
        	<p>{{ __('The meta description tag is good.') }}</p>
        	<p><small>{{ $summaryAuditPages['results'][$name]['value'] }}</small></p>
        </div>
        @else
        	 @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		<div class="uk-width-3-4 ">
                    @if($error == 'missing')
                        {{ __('The meta description tag is missing or empty.') }}
                    @endif
                </div>
        	@endforeach
        @endif

    </div>
</li>
@endif