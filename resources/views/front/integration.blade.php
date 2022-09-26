@extends('layouts.main_layout')
@section('content')
<section class="manage-dashboard-section">
    <div class="container">
        <div class="text-center">
            <h1><strong>Integrate & Monitor All The <br>Marketing Channels</strong></h1>
        </div>
        <div class="filters">
            <ul>
                <li class="active" data-filter="*">All</li>
                <li data-filter=".PPC">PPC </li>
                <li data-filter=".SEO">SEO</li>
                <li data-filter=".CallTracking">Call Tracking</li>
                <li data-filter=".Local">Local</li>
                <li data-filter=".Social">Social</li>
                <li data-filter=".Email">Email</li>
                <li data-filter=".Analytics">Analytics</li>
                <li data-filter=".Ecommerce">Ecommerce</li>
            </ul>
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

                <div class="integration-box all PPC SEO coming_soon">
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

                <div class="integration-box all CallTracking coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-12.png')}}">
                            <figcaption>CallSource</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Local coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-13.png')}}">
                            <figcaption>Campaign Monitor</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Local coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-14.png')}}">
                            <figcaption>Constant Contact</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Local coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-15.png')}}">
                            <figcaption>Delacon</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Local coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-16.png')}}">
                            <figcaption>Dialogtech</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Social coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-17.png')}}">
                            <figcaption>Facebook</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Social coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-17.png')}}">
                            <figcaption>Facebook Ads</figcaption>
                        </figure>
                    </div>
                </div>

                <div class="integration-box all Email coming_soon">
                    <div class="item">
                        <figure>
                            <img src="{{URL::asset('public/front/img/integration-logo-19.png')}}">
                            <figcaption>Gatherup</figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="shape1 rellax" data-rellax-speed="3">
        <img src="{{URL::asset('public/front/img/shape-1.png')}}">
    </div>
</section>

<section class="our-features-section">
    <div class="container">
        <div class="text-center mb-5" >
            <h2>Our <strong>Features </strong></h2>
            <p>A feature-rich reporting tool for marketing agencies to successfully manage hundreds of clients.</p>
        </div>

        <div class="row justify-content-center">

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/rank-tracker-icon.png')}}" alt="Rank tracker">
                    </figure>
                    <h4>Rank Tracker </h4>
                    <p>Monitor how keywords are fairing up on popular search engine result pages.</p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/fully-branded-icon.png')}}" alt="Fully Branded">
                    </figure>
                    <h4>100% White Label</h4>
                    <p>Enhance brand recognition with a company logo, colour scheme & brand message.</p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/automated-reports-icon.png')}}" alt="Automated Reports">
                    </figure>
                    <h4>Automated Reports</h4>
                    <p>Send timely campaign performance reports-daily, weekly or monthly.</p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/agency-management-icon.png')}}" alt="Agency management">
                    </figure>
                    <h4>Agency Management</h4>
                    <p>Managing regular and one-off tasks for agency clients and your staff. </p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/custom-dashboard-icon.png')}}" alt="All-in-one Dashboard">
                    </figure>
                    <h4>All-in-one Dashboard </h4>
                    <p>See your SEO, PPC , GMB on single dashboard.</p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/google-integrations-icon.png')}}" alt="google integrations">
                    </figure>
                    <h4><img src="{{URL::asset('public/front/img/google-icon.png')}}" alt="Google integrations">Integrations</h4>
                    <p>Extract insightful data from numerous integrations with other marketing platforms. </p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/keyword-research-icon.png')}}" alt="Keyword Research">
                    </figure>
                    <h4>Keyword Research</h4>
                    <p>Discover keyword ideas instantly and ranking opportunities that you won't find elsewhere. </p>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="post-with-icon-left">
                    <figure>
                        <img src="{{URL::asset('public/front/img/site-audit-icon.png')}}" alt="Site Audit">
                    </figure>
                    <h4>Site Audit</h4>
                    <p>Track what's your website's health score? </p>
                </div>
            </div>

        </div>

    </div>

    <div class="shape2 rellax" data-rellax-speed="4">
        <img src="{{URL::asset('public/front/img/shape-2.png')}}">
    </div>
</section>
@endsection