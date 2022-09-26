@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('In-page links') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p> {{ __('The number of links on the webpage is okay.') }}</p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                <p>
                    @if($error == 'too_many')
                        {{ __('The webpage contains more than :value links.', ['value' => number_format($details['max'], 0, __('.'), __(','))]) }}
                    @endif
                </p>
                @endforeach
            @endif
            
            @if($summaryAuditPages['results'][$name]['value'])
            <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                @foreach($summaryAuditPages['results'][$name]['value'] as $key => $value)
                 <li>
                    <a class="uk-accordion-title" href="#">
                        {{ $key }} <span class="count-badge badge-secondary">{{ number_format(count($value), 0, __('.'), __(',')) }}</span>
                    </a>
                    
                </li>
                @endforeach
            </ul>
            @endif

        </div>
    </div>
</li>
@endif