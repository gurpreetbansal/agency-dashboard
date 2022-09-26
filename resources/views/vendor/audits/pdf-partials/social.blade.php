@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Social') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>{{ __('The webpage has :value social links.', ['value' => number_format(array_sum(array_map('count', $summaryAuditPages['results'][$name]['value'])), 0, __('.'), __(','))]) }}</p>

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
            @else

                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                    <p>
                        @if($error == 'missing')
                            {{ __('The webpage does not contain any social links.') }}
                        @endif
                    </p>
                @endforeach
               
            @endif
        
        </div>
    </div>
</li>
@endif