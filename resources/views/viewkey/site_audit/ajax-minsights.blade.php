@php
@$auditPass = @$auditPassDiagno = '';
@$auditPassed = 0;
@endphp

<div class="elem-flex border-bottom py-4">
    <div class="elem-start uk-width-expand pr-0 uk-flex-middle px-4">
        <div class="circle-donut" style="width:208px;height:208px;">
            <div class="circle_inbox"><span class="percent_text">{{ (int)round((($mobileInsights['lighthouseResult']['audits']['first-contentful-paint']['score'])*10) + (($mobileInsights['lighthouseResult']['audits']['interactive']['score'])*10) + (($mobileInsights['lighthouseResult']['audits']['total-blocking-time']['score'])*30) + (($mobileInsights['lighthouseResult']['audits']['speed-index']['score'])*10) + (($mobileInsights['lighthouseResult']['audits']['largest-contentful-paint']['score'])*15) + (($mobileInsights['lighthouseResult']['audits']['cumulative-layout-shift']['score'])*15)) }}</span> of 100</div>
            <input type="hidden" class="dinsightsAuditChart" value="{{ (int)round((($mobileInsights['lighthouseResult']['audits']['first-contentful-paint']['score'])*10) + (($mobileInsights['lighthouseResult']['audits']['interactive']['score'])*10) + (($mobileInsights['lighthouseResult']['audits']['total-blocking-time']['score'])*30) + (($mobileInsights['lighthouseResult']['audits']['speed-index']['score'])*10) + (($mobileInsights['lighthouseResult']['audits']['largest-contentful-paint']['score'])*15) + (($mobileInsights['lighthouseResult']['audits']['cumulative-layout-shift']['score'])*15)) }}">
            <canvas id="dinsights-audit-chart" width="50" height="50"></canvas>
        </div>

        <div class="" style="display: none;"></div>
        <div class="score-for">
            <h2><small>Desktop PageSpeed Insights</small></h2>
            <div class="border uk-border-rounded py-1 px-3 uk-width-1-3 uk-flex-between uk-flex mt-4">
                <div class="uk-flex uk-flex-middle"><span class="highlight-color green-light mr-2"></span> 0-49</div>
                <div class="uk-flex uk-flex-middle"><span class="highlight-color orange-light mr-2"></span> 50-89</div>
                <div class="uk-flex uk-flex-middle"><span class="highlight-color red-light mr-2"></span> 90-100</div>
            </div>
        </div>
    </div>
</div>

@if(isset($mobileInsights['originLoadingExperience']))
<div class="border-bottom py-4">
    <h6 class="uk-text-normal px-4 mb-0 py-1">
        <div class="uk-grid">
            <div class="uk-width-1-3">Field Data</div>
            <div class="uk-width-2-3">
                <small>Over the previous 28-day collection period, <a href="#"><u>field data</u></a> shows that this page does not pass the <a href="#"><u>Core Web Vitals</u></a> assessment.</small>
            </div>
        </div>
    </h6>

    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> First Contentful Paint (FCP)</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['originLoadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']/1000 }}
                    s</div>
                    <div class="uk-width-1-2">
                        <div class="color-bar">
                            @foreach($mobileInsights['originLoadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions']
                            as $key => $value)
                            @if($key == 0)
                            <span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @elseif($key == 1)
                            <span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @else
                            <span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }}"
                class="sub-2 mr-1" alt=""> First Input Delay (FID)</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">

                        {{@$mobileInsights['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['percentile']/1000}}s
                    </div>
                    <div class="uk-width-1-2">
                        <div class="color-bar">
                            @if(isset($mobileInsights['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']))
                            @foreach($mobileInsights['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions']
                            as $key => $value)
                            @if($key == 0)
                            <span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @elseif($key == 1)
                            <span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @else
                            <span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt=""> Largest Contentful Paint (LCP)</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{@$mobileInsights['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile']/1000}}s
                    </div>
                    <div class="uk-width-1-2">
                        <div class="color-bar">
                            @if(isset($mobileInsights['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']))
                            @foreach($mobileInsights['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions']
                            as $key => $value)
                            @if($key == 0)
                            <span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @elseif($key == 1)
                            <span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @else
                            <span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                class="sub-2 mr-1" alt=""> Cumulative Layout Shift (CLS)</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{@$mobileInsights['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile']/1000}}s
                    </div>
                    <div class="uk-width-1-2">
                        <div class="color-bar">
                            @if(isset($mobileInsights['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']))
                            @foreach($mobileInsights['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions']
                            as $key => $value)
                            @if($key == 0)
                            <span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @elseif($key == 1)
                            <span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @else
                            <span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<?php //echo "<pre/>"; print_r($mobileInsights['lighthouseResult']['audits']); die; ?>
<div class="border-bottom py-4">
    <h6 class="uk-text-normal px-4 mb-0 py-1">Lab Data</h6>
    <ul uk-accordion class="content-accr p-0 m-0 ">
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-3">
                        @if($mobileInsights['lighthouseResult']['audits']['first-contentful-paint']['score'] <= 1.8)
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @elseif($mobileInsights['lighthouseResult']['audits']['first-contentful-paint']['score'] <= 3)
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @else
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @endif
                    

                        {{@$mobileInsights['lighthouseResult']['audits']['first-contentful-paint']['title']}}
                    </div>
                    <div class="uk-width-2-3">
                     {{@$mobileInsights['lighthouseResult']['audits']['first-contentful-paint']['displayValue']}}
                     </div>
                 </div>
             </div>
             <div class="uk-accordion-content p-4 mt-0 ">
                <p>First Contentful Paint marks the time at which the first text or image is painted. <a target="_blank" href="https://web.dev/first-contentful-paint/" >Learn more.</a> </p>
            </div>
        </li>

        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-3">
                        @if($mobileInsights['lighthouseResult']['audits']['interactive']['score'] <= 3.8)
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @elseif($mobileInsights['lighthouseResult']['audits']['interactive']['score'] <= 7.3)
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @else
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @endif

                    {{ @$mobileInsights['lighthouseResult']['audits']['interactive']['title'] }} </div>
                    <div class="uk-width-2-3">
                    {{ @$mobileInsights['lighthouseResult']['audits']['interactive']['displayValue'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Time to interactive is the amount of time it takes for the page to become fully interactive. <a target="_blank" href="https://web.dev/interactive/" > Learn more.</a></p>
            </div>
        </li>

        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-3">
                        @if($mobileInsights['lighthouseResult']['audits']['total-blocking-time']['score'] <= 200)
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @elseif($mobileInsights['lighthouseResult']['audits']['total-blocking-time']['score'] <= 600)
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @else
                            <img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        @endif
                        
                    {{ @$mobileInsights['lighthouseResult']['audits']['total-blocking-time']['title'] }} </div>
                    <div class="uk-width-2-3">
                    {{ @$mobileInsights['lighthouseResult']['audits']['total-blocking-time']['displayValue'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Sum of all time periods between FCP and Time to Interactive, when task length exceeded 50ms, expressed in milliseconds. <a target="_blank" href="https://web.dev/lighthouse-total-blocking-time/" >Learn more.</a> </p>
            </div>
        </li>

        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} "  class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['speed-index']['title'] }} </div>
                    <div class="uk-width-2-3">
                    {{ @$mobileInsights['lighthouseResult']['audits']['speed-index']['displayValue'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p> Speed Index shows how quickly the contents of a page are visibly populated. <a target="_blank" href="https://web.dev/lighthouse-total-blocking-time/" >Learn more.</a> </p>
            </div>
        </li>

        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['largest-contentful-paint']['title'] }}</div>
                    <div class="uk-width-2-3">
                        {{ @$mobileInsights['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] }}
                    </div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>This is the largest contentful element painted within the viewport. <a target="_blank" href="https://web.dev/lighthouse-total-blocking-time/" >Learn more.</a> </p>
            </div>
        </li>
        <li>
            <div class="uk-accordion-title px-4 py-3">
                <div class="uk-grid ">
                    <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images//cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['cumulative-layout-shift']['title'] }}</div>
                    <div class="uk-width-2-3">
                        {{ @$mobileInsights['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue'] }}
                    </div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Cumulative Layout Shift measures the movement of visible elements within the viewport.  <a target="_blank" href="https://web.dev/lighthouse-total-blocking-time/" >Learn more.</a> </p>
            </div>
        </li>
    </ul>

    <div class="px-4 mt-4">
        <p> Values are estimated and may vary. The performance score is calculated directly from these metrics.</p>
        <div class="data-img">
            <div class="uk-flex uk-flex-wrap uk-flex-wrap-around">
                @if(isset($mobileInsights['lighthouseResult']['audits']['screenshot-thumbnails']['details']['items']))
                @foreach($mobileInsights['lighthouseResult']['audits']['screenshot-thumbnails']['details']['items'] as
                $keyImg => $valueImg)
                <span><img src="{{ $valueImg['data'] }}" alt="" /></span>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>


<div class="border-bottom py-4">
    <h6 class="uk-text-normal px-4 mb-0 py-1">Opportunities</h6>

    @if((int) @$mobileInsights['lighthouseResult']['audits']['server-response-time']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                class="sub-2 mr-1"
                alt="">{{ @$mobileInsights['lighthouseResult']['audits']['server-response-time']['title'] }}</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['lighthouseResult']['audits']['server-response-time']['displayValue'] }}
                    </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color red-light mr-2 uk-width-expand"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php 
    $auditPassed++;
    $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['server-response-time']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['server-response-time']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['modern-image-formats']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                class="sub-2 mr-1"
                alt="">{{ @$mobileInsights['lighthouseResult']['audits']['modern-image-formats']['title'] }}</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['lighthouseResult']['audits']['modern-image-formats']['displayValue'] }}
                    </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color red-light mr-2 uk-width-expand"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['modern-image-formats']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['modern-image-formats']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['unused-javascript']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['unused-javascript']['title'] }}</div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                    {{ @$mobileInsights['lighthouseResult']['audits']['unused-javascript']['displayValue'] }}</div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
     @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['unused-javascript']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['unused-javascript']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['efficient-animated-content']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['efficient-animated-content']['title'] }}
            </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['lighthouseResult']['audits']['efficient-animated-content']['displayValue'] }}
                    </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['efficient-animated-content']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['efficient-animated-content']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['render-blocking-resources']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['render-blocking-resources']['title'] }}
            </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['lighthouseResult']['audits']['render-blocking-resources']['displayValue'] }}
                    </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['render-blocking-resources']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['render-blocking-resources']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['uses-optimized-images']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['uses-optimized-images']['title'] }}
            </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['lighthouseResult']['audits']['uses-optimized-images']['displayValue'] }}
                    </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['uses-optimized-images']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['uses-optimized-images']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['uses-responsive-images']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['uses-responsive-images']['title'] }}
            </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                        {{ @$mobileInsights['lighthouseResult']['audits']['uses-responsive-images']['displayValue'] }}
                    </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['uses-responsive-images']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['uses-responsive-images']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['usesunused-css-rules']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['unused-css-rules']['title'] }} </div>
                <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                    {{ @$mobileInsights['lighthouseResult']['audits']['unused-css-rules']['displayValue'] }} </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['unused-css-rules']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['unused-css-rules']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['offscreen-images']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img
                src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1"
                alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['offscreen-images']['title'] }} </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">
                    {{ @$mobileInsights['lighthouseResult']['audits']['offscreen-images']['displayValue'] }} </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['offscreen-images']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['offscreen-images']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['uses-rel-preload']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['uses-rel-preload']['title'] }} </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">{{ @$mobileInsights['lighthouseResult']['audits']['uses-rel-preload']['displayValue'] }} </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['uses-rel-preload']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['uses-rel-preload']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

    @if((int) @$mobileInsights['lighthouseResult']['audits']['preload-lcp-image']['numericValue'] >= 600)
    <div class="px-4 py-3">
        <div class="uk-grid">
            <div class="uk-width-1-3"><img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt=""> {{ @$mobileInsights['lighthouseResult']['audits']['preload-lcp-image']['title'] }} </div>
            <div class="uk-width-2-3">
                <div class="uk-grid">
                    <div class="uk-width-auto">{{ @$mobileInsights['lighthouseResult']['audits']['preload-lcp-image']['displayValue'] }} </div>
                    <div class="uk-width-1-4">
                        <span class="highlight-color orange-light mr-2 uk-width-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <?php $auditPassed++; $auditPass .= '<div class="px-4 py-3"> <div class="uk-grid"> <div class="uk-width-1-3"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt="">'. @$mobileInsights['lighthouseResult']['audits']['preload-lcp-image']['title'] .'</div><div class="uk-width-2-3"><div class="uk-grid"> <div class="uk-width-auto">'. @$mobileInsights['lighthouseResult']['audits']['preload-lcp-image']['displayValue'] .' </div> <div class="uk-width-1-4"> <span class="highlight-color green-light mr-2 uk-width-expand"></span></div></div> </div></div></div>'; ?>
    @endif

</div>


<div class="border-bottom py-4">
    <h6 class="uk-text-normal px-4 mb-0 py-1">
         <div class="uk-grid">
            <div class="uk-width-1-3">Diagnostics</div>
            <div class="uk-width-2-3">
                <small>These suggestions can help your page load faster. They don't directly affect the Performance score.</small>
            </div>
        </div> 
    </h6>
    <ul uk-accordion class="content-accr p-0 m-0 ">

        @if((int) @$mobileInsights['lighthouseResult']['audits']['uses-long-cache-ttl']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1" alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['uses-long-cache-ttl']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>A long cache lifetime can speed up repeat visits to your page. <a target="_blank" href="https://web.dev/uses-long-cache-ttl/" > Learn more</a></p>
            </div>
        </li>
        @else

            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['uses-long-cache-ttl']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 "> <p>A long cache lifetime can speed up repeat visits to your page. <a target="_blank" href="https://web.dev/uses-long-cache-ttl/" > Learn more </a></p> </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['font-display']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1">
                        <img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['font-display']['title'] }} 
                    </div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Leverage the font-display CSS feature to ensure text is user-visible while webfonts are loading. <a target="_blank" href="https://web.dev/font-display/" > Learn more</a></p>
            </div>
        </li>
        @else

            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['font-display']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 "> <p>Leverage the font-display CSS feature to ensure text is user-visible while webfonts are loading. <a target="_blank" href="https://web.dev/font-display/" > Learn more</a></p> </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['uses-passive-event-listeners']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
                        {{ @$mobileInsights['lighthouseResult']['audits']['uses-passive-event-listeners']['title'] }}
                    </div>

                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
               <p>Consider marking your touch and wheel event listeners as `passive` to improve your page's scroll performance.  <a target="_blank" href="https://web.dev/uses-passive-event-listeners/" > Learn more</a></p> 
            </div>
        </li>
        @else

            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['uses-passive-event-listeners']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Consider marking your touch and wheel event listeners as `passive` to improve your page\'s scroll performance.  <a target="_blank" href="https://web.dev/uses-passive-event-listeners/" > Learn more</a></p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['unsized-images']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1 " alt=" " />
                        {{ @$mobileInsights['lighthouseResult']['audits']['unsized-images']['title'] }}
                    </div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Set an explicit width and height on image elements to reduce layout shifts and improve CLS. <a target="_blank" href="https://web.dev/optimize-cls/#images-without-dimensions" > Learn more </a> </p>
            </div>
        </li>
        @else

            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['unsized-images']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Set an explicit width and height on image elements to reduce layout shifts and improve CLS. <a target="_blank" href="https://web.dev/optimize-cls/#images-without-dimensions" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['total-byte-weight']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['total-byte-weight']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Large network payloads cost users real money and are highly correlated with long load times. <a target="_blank" href="https://web.dev/total-byte-weight/" > Learn more </a> </p> 
            </div>
        </li>
        @else

            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['total-byte-weight']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Large network payloads cost users real money and are highly correlated with long load times. <a target="_blank" href="https://web.dev/total-byte-weight/" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['mainthread-work-breakdown']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['mainthread-work-breakdown']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
               <p>Consider reducing the time spent parsing, compiling and executing JS. You may find delivering smaller JS payloads helps with this. <a target="_blank" href="https://web.dev/mainthread-work-breakdown/" > Learn more </a>
            </div>
        </li>
        @else

            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['mainthread-work-breakdown']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Consider reducing the time spent parsing, compiling and executing JS. You may find delivering smaller JS payloads helps with this. <a target="_blank" href="https://web.dev/mainthread-work-breakdown/" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['critical-request-chains']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['critical-request-chains']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
               <p>The Critical Request Chains below show you what resources are loaded with a high priority. Consider reducing the length of chains, reducing the download size of resources, or deferring the download of unnecessary resources to improve page load.  <a target="_blank" href="https://web.dev/critical-request-chains/" > Learn more </a> </p>
            </div>
        </li>
        @else
            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['critical-request-chains']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>The Critical Request Chains below show you what resources are loaded with a high priority. Consider reducing the length of chains, reducing the download size of resources, or deferring the download of unnecessary resources to improve page load.  <a target="_blank" href="https://web.dev/critical-request-chains/" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['user-timings']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['user-timings']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Consider instrumenting your app with the User Timing API to measure your app\'s real-world performance during key user experiences.  <a target="_blank" href="https://web.dev/user-timings/" > Learn more </a> </p>
            </div>
        </li>
        @else
            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['user-timings']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Consider instrumenting your app with the User Timing API to measure your app\'s real-world performance during key user experiences.  <a target="_blank" href="https://web.dev/user-timings/" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['resource-summary']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['resource-summary']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Consider instrumenting your app with the User Timing API to measure your app\'s real-world performance during key user experiences.  <a target="_blank" href="https://web.dev/user-timings/" > Learn more </a> </p>
            </div>
        </li>
        @else
            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['resource-summary']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Consider instrumenting your app with the User Timing API to measure your app\'s real-world performance during key user experiences.  <a target="_blank" href="https://web.dev/user-timings/" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['third-party-facades']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['third-party-facades']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Consider instrumenting your app with the User Timing API to measure your app\'s real-world performance during key user experiences.  <a target="_blank" href="https://web.dev/user-timings/" > Learn more </a> </p>
            </div>
        </li>
        @else
            <?php $auditPassed++; $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['third-party-facades']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Consider instrumenting your app with the User Timing API to measure your app\'s real-world performance during key user experiences.  <a target="_blank" href="https://web.dev/user-timings/" > Learn more </a> </p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['largest-contentful-paint-element']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1 " alt=" " />
                        {{ @$mobileInsights['lighthouseResult']['audits']['largest-contentful-paint-element']['title'] }}
                    </div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>This is the largest contentful element painted within the viewport. <a target="_blank" href="https://web.dev/lighthouse-largest-contentful-paint/" > Learn more </a></p>
            </div>
        </li>
        @else
            <?php $auditPassed++; 
            $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['largest-contentful-paint-element']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>This is the largest contentful element painted within the viewport. <a target="_blank" href="https://web.dev/lighthouse-largest-contentful-paint/" > Learn more </a></p>  </div>  </li>';
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['layout-shift-elements']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['layout-shift-elements']['title'] }}
                </div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>These DOM elements contribute most to the CLS of the page.</p>
            </div>
        </li>
        @else
            <?php $auditPassed++; 
                $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['layout-shift-elements']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>These DOM elements contribute most to the CLS of the page.</p>  </div>  </li>';

            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['long-tasks']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3 border-bottom">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['long-tasks']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Lists the longest tasks on the main thread, useful for identifying worst contributors to input delay. <a target="_blank" href="https://web.dev/long-tasks-devtools/" > Learn more </a></p>
            </div>
        </li>
        @else
            <?php 
            $auditPassed++; 
            $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['long-tasks']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Lists the longest tasks on the main thread, useful for identifying worst contributors to input delay. <a target="_blank" href="https://web.dev/long-tasks-devtools/" > Learn more </a></p> </div>  </li>';

           
            ?>
        @endif

        @if((int) @$mobileInsights['lighthouseResult']['audits']['non-composited-animations']['numericValue'] >= 600)
        <li>
            <div class="uk-accordion-title px-4 py-3">
                <div class="uk-grid ">
                    <div class="uk-width-1-1"><img
                        src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }}"
                        class="sub-2 mr-1 " alt=" " />
                    {{ @$mobileInsights['lighthouseResult']['audits']['non-composited-animations']['title'] }}</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0 ">
                <p>Animations which are not composited can be janky and increase CLS. <a target="_blank" href="https://web.dev/non-composited-animations" > Learn more </a></p>
            </div>
        </li>
        @else
            <?php 
            $auditPassed++; 
            $auditPassDiagno .= '<li><div class="uk-accordion-title px-4 py-3 border-bottom"><div class="uk-grid "><div class="uk-width-1-1"><img src="'. URL::asset('/public/vendor/internal-pages/images/check-icon.png') .'" class="sub-2 mr-1" alt=" " /> '. @$mobileInsights['lighthouseResult']['audits']['non-composited-animations']['title'] .'</div> </div> </div> <div class="uk-accordion-content p-4 mt-0 ">  <p>Animations which are not composited can be janky and increase CLS. <a target="_blank" href="https://web.dev/non-composited-animations" > Learn more </a></p> </div>  </li>';

            ?>
        @endif

    </ul>
</div>

<div class="py-4">
    <ul uk-accordion class="content-accr p-0 m-0">
        <li>
            <div class="uk-accordion-title px-4 py-2">
                <div class="uk-grid">
                    <div class="uk-width-1-3">
                        <h6 class="uk-text-normal">Passed audit <span class="text-secondary">found ({{ $auditPassed }})</span></h6>
                    </div>
                    <div class="uk-width-2-3">The amount of successfully passed audits.</div>
                </div>
            </div>
            <div class="uk-accordion-content p-4 mt-0">
                {!! $auditPass !!}
                <ul uk-accordion class="content-accr p-0 m-0 ">
                    {!! $auditPassDiagno !!}
                </ul>
            </div>

        </li>
    </ul>
</div>
