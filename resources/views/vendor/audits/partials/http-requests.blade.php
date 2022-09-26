@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('HTTP requests') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>{{ __('There are fewer than :value HTTP requests on the webpage.', ['value' => number_format(array_sum(array_map('count', $summaryAuditPages['results'][$name]['value'])), 0, __('.'), __(','))]) }}</p>
            @else
                <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                @foreach($summaryAuditPages['results'][$name]['value'] as $key => $value)
                 <li>
                    <a class="uk-accordion-title" href="#">
                        {{ $key }} <span class="count-badge badge-secondary">{{ number_format(count($value), 0, __('.'), __(',')) }}</span>
                    </a>
                    <div class="uk-accordion-content">
                        <div class="inner-content">
                            <ul>
                                @foreach($value as $source)
                                <li><a href="{{ $source }}" rel="nofollow" target="_blank">{{ $source ?: __('Empty') }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                @endforeach 
                </ul>
            @endif
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
        <span uk-icon="info"></span>
    </a>
    <div class="uk-accordion-content">
        <div class="inner-content">
            <p>{{ __('The HTTP requests represents the number of external resources present on the webpage.') }}</p>
           
        </div>
    </div>
</li>
@endif