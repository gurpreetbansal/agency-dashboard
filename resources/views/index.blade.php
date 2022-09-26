@extends('layouts.main_layout')
@section('content')

<div class="banner">
  <div class="container">
    <div class="banner-text">
      <div class="left-elem">
        <h1><big>A powerful & data-driven</big> agency reporting tool</h1>
        <p>Tracking & managing multiple marketing campaigns made simpler and faster for agencies via Agency Dashboard.

          Send accurate & automated performance reports on SEO, PPC and GMB to your clients.
        </p>
        <a href="{{route('front.pricing')}}" class="btn btn-orange btn-xl">Start Free Trial</a>
      </div>
      <div class="right-elem">
        <figure data-aos="fade-up" data-aos-duration="1000">
          <img src="{{URL::asset('public/front/img/banner-img.jpg')}}" alt="All-In-One Reporting Platform for Agencies">
        </figure>
      </div>
    </div>
  </div>
</div>

<section class="manage-dashboard-section">
  <div class="container">
    <div class="row">

      <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
        <div class="post-with-icon-cover">

          <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
            <figure>
              <img src="{{URL::asset('public/front/img/seo-icon.png')}}" alt="SEO">
            </figure>
            <h4>SEO</h4>
            <p>Showcase your rankings and search visibility</p>
          </div>

          <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
            <figure>
              <img src="{{URL::asset('public/front/img/ppc-icon.png')}}" alt="PPC">
            </figure>
            <h4>Pay Per Click</h4>
            <p>Track progress of your Google Ads campaign</p>
          </div>

         <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
            <figure>
                <img src="{{URL::asset('public/front/img/my-business-icon.png')}}" alt="My business">
            </figure>
            <h4>Google My Business</h4>
            <p>Find out more about your local SEO</p>
        </div>

        <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
            <figure>
                <img src="{{URL::asset('public/front/img/site-audit-icon.png')}}" alt="site-audit">
            </figure>
            <h4>Site Audit</h4>
            <p>Track what's your website's health score?</p>
        </div>

        </div>
      </div>

      <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 pl-5 align-self-center" data-aos="fade-up" data-aos-duration="1000">
        <h2><big>Dashboard Customised For Your Agency</big></h2>
        <p>A centralized platform, that clubs all the marketing campaigns data in one place for your clients. Engineering a seamless experience for your agency clients to view and review campaign performance, anytime.</p>
        <p>Create an account for your agency on the app and get started.</p>
        <a href="{{route('front.pricing')}}" class="btn btn-blue btn-xl">Sign up</a>
      </div>

    </div>
  </div>

  <div class="shape1 rellax" data-rellax-speed="3">
    <img src="{{URL::asset('public/front/img/shape-1.png')}}">
  </div>
</section>

<section class="our-features-section">
  <span id="OurFeatures" class="blankSpace"></span>
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
      <h2><strong>Our</strong> Features</h2>
      <blockquote>
        <p>A feature-rich reporting tool for marketing agencies to successfully manage hundreds of clients.</p>
      </blockquote>
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
        <h4>All-in-one Dashboard</h4>
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
</section>

<section class="our-case-studies-section">
        <div class="container">
            <div class="heading flex" data-aos="fade-up" data-aos-duration="1000">
                <h2><strong>Integrations</strong></h2>
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

        <div class="shape2 rellax" data-rellax-speed="4">
            <img src="{{URL::asset('public/front/img/shape-2.png')}}">
        </div>
    </section>

<!-- <section class="newsletter-section">
  <div class="container">
    <div class="heading" data-aos="fade-up" data-aos-duration="1000">
      <h2><strong>Try Agency Dashboard</strong></h2>
      <h3><span>Free for 14 Days</span></h3>
    </div>

    <div class="newsletter-form" data-aos="fade-up" data-aos-duration="1000">
      <form action="{{route('front.pricing')}}">
        <input type="text" placeholder="Enter your email">
        <button type="submit" onclick="return false;"><i class="fa fa-long-arrow-right"></i></button>
      </form>
    </div>

  </div>
</section> -->
@endsection