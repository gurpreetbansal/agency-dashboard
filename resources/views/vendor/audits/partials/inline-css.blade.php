@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Inline CSS') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The webpage does not contain inline CSS code.') }}</p>
                @else
                        @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                        <p>
                                @if($error == 'failed')
                                {{ __('The webpage contains inline CSS code.') }}
                                @endif
                        </p>
                        <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                        
                         <li>
                            <a class="uk-accordion-title" href="#">
                                {{ __('Attributes') }} <span class="count-badge badge-secondary">{{ number_format(count($details), 0, __('.'), __(',')) }}</span>
                            </a>
                            <div class="uk-accordion-content">
                                <div class="inner-content">
                                    <ul>
                                        @foreach($details as $value)
                                        <li><code>{{ $value }}</code></li>
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
			<p>  {{ __('The style attribute contains CSS style rules that are applied to the element.') }} {{ __('Inline CSS code unnecessarily increases the webpage\'s size, and can be moved in an external CSS file.') }} </p>
                        <hr>
                        <p>Learn more 
                                <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/style" target="_blank" rel="nofollow" >Mozilla <span uk-icon="arrow-right"></span></a>
                        </p>
			
		</div>
	</div>
</li>
@endif