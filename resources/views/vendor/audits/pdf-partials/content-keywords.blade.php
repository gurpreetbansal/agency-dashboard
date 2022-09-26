@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
            	{{ __('Content keywords') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
	        	<p>{{ __('The content has relevant keywords.') }}</p>
	        	<div class="badge-flex">
	        		@foreach($summaryAuditPages['results'][$name]['value'] as $keyword)
	                    <span class="badge-success">{{ $keyword }}</span>
	                @endforeach
	            </div>
	        @else
            	@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
            		<div class="uk-width-3-4 ">
	                    @if($error == 'missing')
                            {{ __('No relevant keywords found on the webpage.') }}

                            <div class="badge-flex">
                                @foreach($details as $keyword)
                                    <span class="badge-secondary badge-danger">{{ $keyword }}</span>
                                @endforeach
                            </div>
                         @endif
                    </div>
            	@endforeach
            @endif
        </div>
    </div>
</li>
@endif