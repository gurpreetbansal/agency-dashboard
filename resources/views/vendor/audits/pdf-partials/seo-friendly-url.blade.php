@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('SEO friendly URL') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>
                    {{ __('The URL is SEO friendly.') }}
                </p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                 @if($error == 'bad_format')
                   <p> {{ __('The URL is not SEO friendly.') }}</p>
                @endif

                @if($error == 'missing')
                   <p> {{ __('The URL does not contain any relevant keywords.') }}</p>
                @endif
                @endforeach
            @endif
            <p><small>{{ $summaryAuditPages['results'][$name]['value'] }}</small></p>
        </div>
    </div>
</li>
@endif