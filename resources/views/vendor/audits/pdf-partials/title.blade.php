@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
        		@include('vendor.audits.partials.status')
            	{{ __('Title') }}
            </h5>
        </div>

         @if($summaryAuditPages['results'][$name]['passed'])

        <div class="uk-width-3-4 text-success">
        	<p>{{ __('The title tag is perfect.') }}</p>
        	<p><small>{{ $summaryAuditPages['results'][$name]['value'] }}</small></p>
        </div>
        @else
        	@foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
        		<div class="uk-width-3-4 ">
                    @if($error == 'missing')
                        <p> {{ __('The title tag is missing or empty.') }} </p>
                    @endif

                    @if($error == 'length')
                        <p> {{ __('The title tag must be between :min and :max characters.', ['min' => $details['min'], 'max' => $details['max']]) }} </p>
                        <p><small>{{ __('The current title has :value characters.', ['value' => mb_strlen($summaryAuditPages['results'][$name]['value'])]) }}</small></p>
                        <p><small>{{ $summaryAuditPages['results'][$name]['value'] }}s</small></p>
                    @endif

                    @if($error == 'too_many')
                       <p> {{ __('Only one title tag should be present on the webpage.') }} </p>
                    @endif
                </div>
        	@endforeach
        @endif

    </div>
</li>
@endif