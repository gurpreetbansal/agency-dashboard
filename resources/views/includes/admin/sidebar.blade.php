 <aside class="sidebar">
    <div class="logo">
        <a href="{{url('/')}}">
            <img src="{{URL::asset('public/front/img/logo.png')}}" alt="Logo">
        </a>
    </div>
    <nav>
        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
            <li class="{{ (request()->is('/admin/dashboard')) ? 'active' : '' }}">
                <a href="{{url('/admin/dashboard')}}">
                    <figure><span uk-icon="thumbnails"></span></figure> Dashboard
                </a>
            </li>
            <li class="{{ (request()->is('/admin/feedbacks')) ? 'active' : '' }}">
                <a href="{{url('/admin/feedbacks')}}">
                     <figure><span uk-icon="commenting"></span></figure>Feedbacks
                </a>
            </li>
            <!-- <li class="{{ (request()->is('/site-audit')) ? 'active' : '' }}">
                <a href="{{url('/admin/site-audit')}}">
                    <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure> Site Audit
                </a>
            </li> -->
        </ul>

        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
            <li>
            <a href="{{url('/admin/logout')}}">
                    <figure><img src="{{URL::asset('public/vendor/internal-pages/images/logout-icon.png')}}" alt="Logout"></figure> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>