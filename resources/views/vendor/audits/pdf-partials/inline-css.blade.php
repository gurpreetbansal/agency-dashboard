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
                    </li>
                </ul>
                @endforeach
        	@endif
        </div>
    </div>
</li>
@endif