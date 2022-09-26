@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('JavaScript defer') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>{{ __('The javascript resources have the defer attribute set.') }}</p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                    @if($error == 'missing')
                        <p> {{ __('The are :value javascript resources without the defer attribute.', ['value' => number_format(count($details), 0, __('.'), __(','))]) }} </p>
                    <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                        
                         <li>
                            <a class="uk-accordion-title" href="#">
                                {{ __('JavaScripts') }} <span class="count-badge badge-secondary">{{ number_format(count($details), 0, __('.'), __(',')) }}</span>
                            </a>
                            
                        </li>
                        
                    </ul>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    
</li>
@endif