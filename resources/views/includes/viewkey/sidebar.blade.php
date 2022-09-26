<header class="header viewkey-header">
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
            
        </ul>
    </nav>
</aside>

<div class="overlayLayer"></div>