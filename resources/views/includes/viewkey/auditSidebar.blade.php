<aside class="sidebar">
    <div class="logo">
        @if(isset($profile_data->share_key) && ($profile_data->share_key <> null))
        <a href="{{url('/project-detail/'. @$profile_data->share_key)}}">
            <img src="{{URL::asset('public/front/img/logo.svg')}}" alt="Logo">
        </a>
        @else
        <a href="{{url('/audit-share/'. @$summaryAudit->share_key)}}">
            <img src="{{URL::asset('public/front/img/logo.svg')}}" alt="Logo">
        </a>
        @endif
    </div>

    <nav>
        <div class="uk-navbar-item">
            <form action="javascript:void(0)">
                <input type="text" placeholder="Search..." class="projects_autocomplete">
                <div class="refresh-search-icon" id="refresh-sidebar-search">
                    <span uk-icon="refresh"></span>
                </div>
                <a href="javascript:;" class="sidebar-search-clear"><span class="clear-input sidebarClear" uk-icon="icon: close;"></span></a>
                <button type="submit"><span uk-icon="icon:search"></span></button>
            </form>
        </div>
       
        @php
            @$key = array_search($summaryAudit->url, array_column($summaryAudit->pages->toArray(), 'url'));
        @endphp
        <ul class="uk-nav-default uk-nav-parent-icon" >
            <li class="uk-parent uk-open">
                <a uk-tooltip="title:{{ $summaryAudit->project }}; pos: top-left" href="{{ url('/audit/page/detail') }}/{{ @$summaryAudit->share_key }}/{{ $summaryAudit->pages[$key]->id }}">
                    <figure><img src="{{URL::asset('public/vendor/internal-pages/images/project-icon.png')}}" alt="Projects"></figure>  <span> {{ $summaryAudit->project }} </span>
                </a>

                <ul class="uk-nav-sub" id="defaultCampaignList">
                    @foreach($summaryAudit->pages as $key => $value)
                    @if(parse_url($value->url, PHP_URL_PATH) !== '/' && parse_url($value->url, PHP_URL_PATH) !== '')
                    <li uk-tooltip="title:{{ $value->url }}; pos: top-left" class="{{ $pageId == $value->id ? 'active' : '' }}">
                        <a href="{{ url('/audit/page/detail') }}/{{ @$summaryAudit->share_key }}/{{ $value->id }}">{{ parse_url($value->url, PHP_URL_PATH) }}</a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    </nav>
</aside>


<!-- <header class="header viewkey-header">
    <div class="elem-start">
        <button class="toggleMenuBtn">
            <span uk-icon="icon: menu" class="uk-icon if-close"></span>
            <span uk-icon="icon: close" class="uk-icon if-open"></span>
        </button>
        <div class="logo">
            <a href="{{url('/')}}">
                
                @if(isset($profile_data) && @$profile_data->ProfileInfo->white_label_branding === 1)
                <img src="{{ @$profile_data->logo_data <> null ? @$profile_data->logo_data : URL::asset('public/front/img/logo.svg')}}" alt="logo">
                @else
                <img src="{{ URL::asset('public/front/img/logo.svg')}}" alt="logo">
                @endif
            </a>
        </div>
    </div>
</header>

<aside class="viewkey-sidebar">
    <div class="logo">
        <a href="{{url('/')}}">
            <div class="loader h-91"></div>
            
            @if(isset($profile_data) && @$profile_data->ProfileInfo->white_label_branding === 1)
            <img src="{{ @$profile_data->logo_data <> null ? @$profile_data->logo_data : URL::asset('public/front/img/logo-email.svg')}}" alt="logo">
            @else
            <img src="{{ URL::asset('public/front/img/logo-email.svg')}}" alt="logo">
            @endif
        </a>
    </div>
    <nav>
        <ul class="view-sidebar uk-nav-default uk-nav-parent-icon" uk-switcher="connect: .projectNavContainerSeo" uk-nav>
            @foreach($summaryAudit->pages as $key => $value)
            <li uk-tooltip="title:{{ $value->url }}; pos: top-left">
                <a href="https://waveitdigital.com/audit/page/detail/{{ $summaryAudit->share_key }}/{{ $value->id }}">{{ parse_url($value->url, PHP_URL_PATH) }}</a>
            </li>
            @endforeach

        </ul>
    </nav>
</aside>

<div class="overlayLayer"></div> -->