@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Deprecated HTML') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
        	@if($summaryAuditPages['results'][$name]['passed'])
        		<p>{{ __('There are no deprecated HTML tags on the webpage.') }}</p>
            @else
                <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                @foreach($report['results'][$name]['errors'] as $error => $details)
                @if($error == 'bad_tags')
                 <li>
                    <a class="uk-accordion-title" href="#">
                        {{ __('The webpage has deprecated HTML tags.') }} 
                    </a>
                    <div class="uk-accordion-content">
                        <div class="inner-content">
                            <ul>
                                @foreach($details as $key => $value)
                                <li>
                                    <code>&lt;{{ $key }}&gt;</code>
                                    <span class="badge badge-secondary badge-pill">{{ number_format($value, 0, __('.'), __(',')) }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                @endif
                @endforeach
                </ul>
                    
        	@endif
            	
        </div>
    </div>
</li>
@endif