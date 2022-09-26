@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Character set') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('The webpage has a charset value set.') }}</p>
                        <p><code>{{ $summaryAuditPages['results'][$name]['value'] }}</code></p>
                        
        	@else
        		@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		 @if($error == 'missing')
                               <p> {{ __('The webpage does not have a charset declared.') }} </p>
                        @endif
        		@endforeach
        	@endif
            	
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
    	<span uk-icon="info"></span>
    </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p> {{ __('The meta charset tag specifies the character encoding, helping the browsers to better render the characters and symbols on the webpage.') }}</p>
			<hr>
			<p>Learn more 
                                <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta#attr-charset" target="_blank" rel="nofollow" >Mozilla <span uk-icon="arrow-right"></span></a>
                            
                        </p>
		</div>
	</div>
</li>
@endif