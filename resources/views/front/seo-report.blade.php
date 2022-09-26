@extends('layouts.main_layout')
@section('content')
 <div class="banner inner-banner">
            <div class="container">
                <div class="banner-text">
                    <div class="left-elem">
                        <h1><strong>Your SEO Report</strong></h1>
                        <p>An insightful SEO performance report for agency clients—keyword rankings, backlinks, technical SEO & website analytics. </p>
                        <p><strong>Get A FREE 14-Day Trial </strong></p>
                        <div class="banner-form">
                            <form action="{{route('front.pricing')}}">
                                <input type="text" placeholder="Enter your email">
                                <input type="submit" value="TRY IT FREE" onclick="return false;">
                            </form>
                        </div>
                    </div>
                    <div class="right-elem">
                        <figure data-aos="fade-up" data-aos-duration="1000">
                            <img src="{{URL::asset('public/front/img/seo-report-banner-img.png')}}" alt="SEO Reporting Tool">
                        </figure>
                    </div>
                </div>
            </div>
        </div>

        <section class="manage-dashboard-section report-content">

            <div class="container">

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <figure>
                            <img src="{{URL::asset('public/front/img/live-keyword-tracking.jpg')}}" alt="Backlink Reporting">
                        </figure>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <h2><strong>Keyword</strong> Rankings</h2>
                        <p>Discover and examine the performance of hundreds of keywords on popular search engines—Google, Yahoo and Bing. </p>
                        <p>Agency clients can view how targeted keywords are progressing daily, weekly and monthly—on PCs, smartphones and other devices. </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <figure>
                            <img src="{{URL::asset('public/front/img/seo-report-img-1.png')}}" alt="Competitor Analysis">
                        </figure>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <h2><strong>Backlink</strong> Profile</h2>
                        <p>Examine your website's backlink profile up and close.  Determine from where your website is receiving max links?; are they spammy or  high-quality?; did you lose profitable links over time?</p>
                        <p>Many simple to complicated questions are answered. Easy integration with Ahrefs, Moz, SEMrush and many other tools. </p>
                    </div>
                </div>

               <!--  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <figure>
                            <img src="{{URL::asset('public/front/img/seo-report-img-2.png')}}" alt="SEO Site Audit">
                        </figure>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <h2><strong>Competitor</strong> Analysis</h2>
                        <p>Know your competitors inside out & beat the best in the market. Analyze organic traffic, targeted keywords, backlink profile, referring domains, site authority, social media engagement and so much more.</p>
                        <p>Review SEO strategies & optimize website performance against growing market competition.  </p>
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <figure>
                            <img src="{{URL::asset('public/front/img/seo-report-img-3.png')}}" alt="Website Analysis">
                        </figure>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <h2><strong>SEO</strong> Site Audit</h2>
                        <p>What's your website's health score? Get to know what's not visible to naked eyes. Our technical SEO audit uncovers performance glitches that hinder user experience & negatively impact website ranking on popular SERPs. </p>
                        <p>Fix the broken links, implement redirection.  canonical tags, and so much more. </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <figure>
                            <img src="{{URL::asset('public/front/img/seo-report-img-4.png')}}" alt="Website Analysis">
                        </figure>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="1000">
                        <h2><strong>Website</strong> Analytics</h2>
                        <p>Integrate with Google Analytics and unlock useful performance data about SEO campaigns. Find out the no. of users, average session per page, bounce rate, organic/paid traffic, business leads and more.  </p>
                        <p>Agency clients will know what’s driving or hampering their website’s SEO and marketing ROI. </p>
                    </div>
                </div>

            </div>

            <div class="container">
                <div class="text-center">
                    <a href="/price" class="btn btn-blue">Get A FREE 14-Day Trial</a>
                </div>
            </div>


            <div class="shape1 rellax" data-rellax-speed="3">
                <img src="{{URL::asset('public/front/img/shape-1.png')}}">
            </div>

            <div class="shape2 rellax" data-rellax-speed="4">
                <img src="{{URL::asset('public/front/img/shape-2.png')}}">
            </div>

        </section>
@endsection