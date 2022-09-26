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
                            <div class="uk-accordion-content">
                                <div class="inner-content">
                                    <ul>
                                        @foreach($details as $js)
                                        <li><a href="{{ $js }}" class="text-break" rel="nofollow" target="_blank">{{ $js }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
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
            <p> {{ __('The defer attribute allows the browser to download the scripts in parallel and execute them after the webpage has been parsed, improving the webpage\'s load performance.') }}</p>
           <hr>
            <p>Learn more 
                    <a href="https://web.dev/efficiently-load-third-party-javascript/" target="_blank" rel="nofollow" >Google <span uk-icon="arrow-right"></span></a>
            </p>
        </div>
    </div>
</li>
@endif