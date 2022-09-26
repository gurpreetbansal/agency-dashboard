@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Image format') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>{{ __('The images are served in the :format format.', ['format' => implode(', ', $summaryAuditPages['results'][$name]['value'])]) }}</p>
            @else 
                @foreach($summaryAuditPages['results'][$name]['value'] as  $error => $details)
                    @if($error == 'bad_format')
                        {{ __('There are :value images that are not using the :format format.', ['value' => number_format(count($details), 0, __('.'), __(',')), 'format' => implode(', ', $report['results'][$name]['value'])]) }}
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
                                        <li><a href="{{ $image['url'] }}" class="text-break" rel="nofollow" target="_blank">{{ $image['url'] }}</a></li>
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
</li>
@endif