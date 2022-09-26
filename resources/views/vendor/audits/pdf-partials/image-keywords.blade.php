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
	                </li>
               </ul>
	        @endforeach
	    @endif
    </div>
</div>
</li>
@endif
