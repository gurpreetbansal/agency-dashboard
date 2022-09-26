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
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p> {{ __('The webpage\'s content should contain relevant keywords that can also be found in the title of the webpage.') }}</p>
		</div>
	</div>
</li>
@endif