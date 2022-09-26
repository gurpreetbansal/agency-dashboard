@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
        		@include('vendor.audits.partials.status')
            	
            	{{ __('Title') }}
            </h5>
        </div>

         @if($summaryAuditPages['results'][$name]['passed'])

        <div class="uk-width-3-4 text-success">
        	<p>{{ __('The title tag is perfect.') }}</p>
        	<p><small>{{ $summaryAuditPages['results'][$name]['value'] }}</small></p>
        </div>
        @else
      
        	@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		<div class="uk-width-3-4 ">
                    @if($error == 'missing')
                        <p> {{ __('The title tag is missing or empty.') }} </p>
                    @endif

                    @if($error == 'length')
                        <p> {{ __('The title tag must be between :min and :max characters.', ['min' => $details['min'], 'max' => $details['max']]) }} </p>
                        <p><small>{{ __('The current title has :value characters.', ['value' => mb_strlen($summaryAuditPages['results'][$name]['value'])]) }}</small></p>
                        <p><small>{{ $summaryAuditPages['results'][$name]['value'] }}</small></p>
                    @endif

                    @if($error == 'too_many')
                       <p> {{ __('Only one title tag should be present on the webpage.') }} </p>
                    @endif
                </div>
        	@endforeach
        @endif

    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p>{{ __('The title tag is the HTML element that specifies the title of the webpage.') }} {{ __('The title tag is displayed at the top of your browser, in the search results, as well as in the bookmarks bar.') }}</p>
			<hr>
			<p>Learn more <a target="_blank" href="https://developers.google.com/search/docs/advanced/appearance/title-link#create-descriptive-page-titles">Google <span uk-icon="arrow-right"></span></a></p>
		</div>
	</div>
</li>
@endif