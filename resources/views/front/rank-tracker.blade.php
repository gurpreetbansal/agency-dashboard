@extends('layouts.main_layout')
@section('content')

<div class="rank-tracker-page">
    <div class="container">
        <section class="rank-hero-section">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="left">
                        <h1>Google Rank <br> Tracker Tool</h1>
                        <h6>Track live keyword ranking with Agency Dashboard. Discover how your important keywords are performing on Google.</h6>
                        <a href="{{url('/price')}}" class="btn btn-green">Get 14 days FREE Trial</a>
                    </div>
                </div>
                <div class="col-md-7">
                    <figure class="text-right">
                        <img src="{{URL::asset('public/front/img/live-keyword-tracking.jpg')}}" alt="live-keyword-tracking">
                    </figure>
                </div>
            </div>
        </section>

        <section class="boxs-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="single">
                        <figure><img src="{{URL::asset('public/front/img/box-icon1.png')}}" alt="box-icon1"></figure>
                        <h5>Search Position <br>Tracking</h5>
                        <p>Track the position of local, national and global keywords on Google search engine.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single">
                        <figure><img src="{{URL::asset('public/front/img/box-icon2.png')}}" alt="box-icon2"></figure>
                        <h5>Calculate Keywords Potential</h5>
                        <p>Gain insights on keywords with search volume and competition.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single">
                        <figure><img src="{{URL::asset('public/front/img/box-icon3.png')}}" alt="box-icon3"></figure>
                        <h5>Automatic Alerts</h5>
                        <p>Get automatic notifications on email as keyword rankings change.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="left-right-section">
            <div class="single">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="info-content">
                            <h2>Local and Global Keyword Tracking</h2>
                            <p>Setup your local, national and international Rank tracking with ease.</p>
                            <p>Track keywords with the ZIP code, city, state, and country to stay ahead of your competitors</p>
                            <p>You can choose either to track organic results only or to include SERP features results (featured snippets, images, videos, local packs, and more).</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <figure class="text-right">
                            <img src="{{URL::asset('public/front/img/local-and-global-keyword-tracking.jpg')}}" alt="local-and-global-keyword-tracking">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="single">
                <div class="row align-items-center flex-md-row-reverse">
                    <div class="col-md-5">
                        <div class="info-content pl40">
                            <h2>Desktop and Mobile Rank Tracking</h2>
                            <p>Check the positions in desktop and mobile search for as many keywords as you need. Compare and optimize desktop and mobile rankings to improve your Google visibility.</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <figure>
                            <img src="{{URL::asset('public/front/img/desktop-and-mobile-rank-tracking.jpg')}}" alt="desktop-and-mobile-rank-tracking">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="single">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="info-content">
                            <h2>Multiple Languages <br>Tracking</h2>
                            <h6>Target keywords across multiple languages.</h6>
                            <ul>
                                <li>Track keywords in multiple languages in one campaign.</li>
                                <li>Track as many multilingual keywords as you want for a single campaign.</li>
                                <li>Track multilingual keywords both globally and locally.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <figure class="text-right">
                            <img src="{{URL::asset('public/front/img/multiple-language-tracking.jpg')}}" alt="multiple-language-tracking">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="single">
                <div class="row align-items-center flex-md-row-reverse">
                    <div class="col-md-5">
                        <div class="info-content pl30">
                            <h2>Rank Tracking Filters</h2>
                            <p>Add custom tags to the keywords you want to group together. Mark your focus keywords as favourite. Use the refresh feature whenever you want to see the latest rankings.</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <figure>
                            <img src="{{URL::asset('public/front/img/rank-tacking-filters.jpg')}}" alt="rank-tacking-filters">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="single">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="info-content pr80">
                            <h2>White-Labeled Ranking Reports</h2>
                            <p>Get easy-to-read beautiful color coded Rank tracking reports. Get graphically represented reports. Download reports in different formats like PDF and Excel.</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <figure class="text-right">
                            <img src="{{URL::asset('public/front/img/white-labeled-ranking-reports.jpg')}}" alt="white-labeled-ranking-reports">
                        </figure>
                    </div>
                </div>
            </div>
        </section>

        <div class="text-center spacebtn">
            <a href="{{url('/price')}}" class="btn btn-blue">Try Agency Dashboard for 14 Days</a>
        </div>
    </div>

    <section class="our-case-studies-section pt-5">
        <div class="container">
         <div class="text-center">
            <h2>Integrations</h2>
        </div>

        <div class="filters-content">
            <div class="grid">

                <div class="integration-box all Analytics">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-20.png')}}">
                            <figcaption>Google Ads</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Analytics">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/analytics-integration.png')}}">
                            <figcaption>Google Analytics</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Analytics">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/gmb-integration.png')}}">
                            <figcaption>Google My Business</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Analytics">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/console-integration.png')}}">
                            <figcaption>Google Search Console</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Analytics">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/serpstat-integration.png')}}">
                            <figcaption>Serp Stat</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-1.png')}}">
                            <figcaption>Active Campaign</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-2.png')}}">
                            <figcaption>Adroll</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-4.png')}}">
                            <figcaption>Amazon  ads</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-5.png')}}">
                            <figcaption>Bing Ads</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-6.png')}}">
                            <figcaption>Avanser</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-7.png')}}">
                            <figcaption>Backlink Monitor</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-5.png')}}">
                            <figcaption>Bing Webmaster Tools</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all PPC SEO coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-9.png')}}">
                            <figcaption>Brightlocal</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all CallTracking coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-10.png')}}">
                            <figcaption>Call tracking metrics</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all CallTracking coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-11.png')}}">
                            <figcaption>CallRail</figcaption>
                        </figure>
                    </div>
                </div>

            </div>
            <div class="text-center"><a href="{{route('front.integrations')}}" class="btn btn-blue btn-xl">View All</a></div>
        </div>
    </div>
   
</section>

<section class="feature-section">
    <div class="container">
        <div class="text-center">
            <h2><strong>Everything Your</strong> Agency Needs</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('public/front/img/rank-tracker-icon.png')}}" alt="Rank tracker">
                    </figure>
                    <h4>Rank Tracker</h4>
                    <p>Monitor how keywords are fairing up on popular search engine result pages.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('public/front/img/fully-branded-icon.png')}}" alt="Fully Branded">
                    </figure>
                    <h4>100% White Label</h4>
                    <p>Enhance brand recognition with a company logo, colour scheme & brand message.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('public/front/img/automated-reports-icon.png')}}" alt="Automated Reports">
                    </figure>
                    <h4>Automated Reports</h4>
                    <p>Send timely campaign performance reports-daily, weekly or monthly.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('public/front/img/agency-management-icon.png')}}" alt="Agency management">
                    </figure>
                    <h4>Agency Management</h4>
                    <p>Managing regular and one-off tasks for agency clients and your staff. </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('/public/front/img/custom-dashboard-icon.png')}}" alt="All-in-one Dashboard">
                    </figure>
                    <h4>All-in-one Dashboard</h4>
                    <p>See your SEO, PPC , GMB on single dashboard.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('public/front/img/google-integrations-icon.png')}}" alt="google integrations">
                    </figure>
                    <h4>
                        <img src="{{URL::asset('public/front/img/google-icon.png')}}" alt="Google integrations"> integrations
                    </h4>
                    <p>Extract insightful data from numerous integrations with other marketing platforms. </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('/public/front/img/keyword-research-icon.png')}}" alt="Keyword Research">
                    </figure>
                    <h4>Keyword Research</h4>
                    <p>Discover keyword ideas instantly and ranking opportunities that you won't find elsewhere. </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="single">
                    <figure>
                        <img src="{{URL::asset('/public/front/img/site-audit-icon.png')}}" alt="Site Audit">
                    </figure>
                    <h4>Site Audit</h4>
                    <p>Track what's your website's health score? </p>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

@endsection