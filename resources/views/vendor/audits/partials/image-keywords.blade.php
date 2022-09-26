@if(isset($summaryAuditPages['results'][$name]))
<li>
<div class="uk-grid">
    <div class="uk-width-1-4">
    	<h5>
        	@include('vendor.audits.partials.status')
        	{{ __('Image keywords') }}
        </h5>
    </div>
    <div class="uk-width-3-4">
    	@if($summaryAuditPages['results'][$name]['passed'])
            <p>
                {{ __('All images have alt attributes set.') }}
            </p>
        @else
	        @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
	        	 @if($error == 'missing')
                     <p> {{ __('There are :value images with missing alt attributes.', ['value' => number_format(count($details), 0, __('.'), __(','))]) }}  </p>
                @endif
	        	<ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
	                <li>
	                    <a class="uk-accordion-title" href="#">
	                        {{ __('Images') }} <span class="count-badge badge-secondary">{{ number_format(count($details), 0, __('.'), __(',')) }}</span>
	                    </a>
	                    <div class="uk-accordion-content">
	                        <div class="inner-content">
	                            <ul>
	                                @foreach($details as $image)
	                                <li><small><a target="_blank" href="{{ $image['url'] }}"> {{ $image['url'] }} </a></small> </li>
	                                @endforeach
	                            </ul>
	                        </div>
	                    </div>
	                </li>
               </ul>
	        @endforeach
	    @endif
    	
    </div>
</div>
<a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
	<span uk-icon="info"></span>
</a>
<div class="uk-accordion-content">
	<div class="inner-content">
		<p>The alt attribute specifies an alternate text for an image, if the image cannot be displayed. The alt attribute is also useful for search engines to identify the subject of the image, and helps screen readers describe the image.</p>
		<hr>
		<p>Learn more <a target="_blank" href="https://developers.google.com/search/docs/advanced/guidelines/google-images">Google <span uk-icon="arrow-right"></span></a></p>
	</div>
</div>
</li>
@endif
