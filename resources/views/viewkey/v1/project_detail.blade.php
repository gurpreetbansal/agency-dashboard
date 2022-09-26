
@extends('layouts.view_key_layout')
@section('content')

<input type="hidden" name="key" id="encriptkey" value="{{ $keyenc }}">
<input type="hidden" class="campaignID" name="campaign_id" value="{{ $campaign_id }}">
<input type="hidden" class="campaign_id" name="campaign_id" value="{{ $campaign_id }}">
<input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">
<input type="hidden" class="audit-id" name="audit_id" value="{{ @$audits->id }}">
<input type="hidden" class="audit-type" name="audit_id" value="individual-audit">

@php
@$dashUsed = array_intersect($types,array_keys($all_dashboards));
@$dashDiff = array_diff(array_keys($all_dashboards),$types);
@$arrCombine = array_merge($dashUsed,$dashDiff);
@endphp

<div class="project-detail-body viewkey-project-detail-body">
    <!-- Project Tabs Nav -->
    <div class="tabs view-ajax-tabs ajax-loader h-48 half">
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .projectNavContainer" swiping="false">
        @foreach($arrCombine as $key=> $dashboards)
            @if(array_key_exists($dashboards,$all_dashboards))
                @if(in_array($dashboards, $dashDiff))
                    <?php $classes = 'selectedDashboardView inactive'; ?>
                @else
                    <?php $classes = $key == 0 ? 'selectedDashboardView uk-active' : 'selectedDashboardView'; ?>
                @endif

                <li id="{{$all_dashboards[$dashboards]}}_tab" class="{{ $classes }}" {{  in_array($dashboards, $dashDiff) ? 'uk-tooltip="title:App not connected; pos: top-left"' : '' }}">
                    <a href="#{{$all_dashboards[$dashboards]}}">
                    {{$all_dashboards[$dashboards]}} <span>Dashboard</span></a>
                </li>
            @endif
        @endforeach
        </ul>

        <a href="{{ url('/download/pdf/'.@$keyenc.'/seo') }}" data-type="seo"  target="_blank" class="btn icon-btn color-red viewkeypdf generate-pdf" uk-tooltip="title: Generate PDF File; pos: top-center" title="" aria-expanded="false"><img src="https://imark.agencydashboard.io/public/vendor/internal-pages/images/pdf-icon.png"></a>
    </div>
    </div>
    <!-- Project Tabs Nav End -->

    <!-- Project Tabs Content -->

    <div class="tab-content ">
        <div class="uk-switcher projectNavContainer">
            @foreach($arrCombine as $key=> $dashboards)
            @if($key==0)
            <div  id="{{$all_dashboards[$dashboards]}}" class="uk-active">
                @if($all_dashboards[$dashboards] =='SEO')
                    @include('viewkey.dashboards.seo')

                @elseif($all_dashboards[$dashboards]=='PPC')
                    @include('viewkey.dashboards.ppc')

                @elseif($all_dashboards[$dashboards] == 'GMB')
                    @include('viewkey.dashboards.gmb')

                @elseif($all_dashboards[$dashboards]=='Social')
                    @include('viewkey.dashboards.social')

                @endif
            </div>
            @else
            <div id="{{$all_dashboards[$dashboards]}}"></div>
            @endif
            @endforeach
        </div>

        <div class="uk-switcher projectNavContainerSideBar">
        </div>
    </div>
    <!-- Project Tabs Content End -->
</div>
@endsection

