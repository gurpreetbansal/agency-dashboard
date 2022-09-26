@extends('layouts.vendor_internal_pages')
@section('content')
<div class="social-section">
    <div class="tab-head white-box">
        <span uk-icon="facebook"></span>
        <h2>Facebook</h2>
        <input type="hidden" class="campaignID" value="{{$campaignId}}">
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: #social_tabs; animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium">
            <li class="uk-active"><a href="javascript:void(0)">Likes</a></li>
            <li><a href="javascript:void(0)">Engagement</a></li>
            <li><a href="javascript:void(0)">Reach</a></li>
            <li><a href="javascript:void(0)">Posts</a></li>
            <li><a href="javascript:void(0)">Reviews</a></li>
        </ul> 
        <p style="margin: 0 0 0 auto;">Last 90 Days</p>      
    </div>
    <div id="social_tabs" class="uk-switcher">
        <div class="uk-active">
            <div class="grid-tab">
                <div uk-grid class="uk-grid uk-child-width-expand">
                    <div>
                        <div uk-grid class="uk-grid uk-child-width-expand">
                            <div>
                                <div class="white-box">
                                    <div class="white-box-head">
                                        <h5>Total Likes</h5>
                                    </div>
                                    <div class="white-box-body">
                                        <h6 class="total-likes">0</h6>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="white-box">
                                    <div class="white-box-head">
                                        <h5>Organic vs Paid Likes</h5>
                                    </div>
                                    <div class="white-box-body">
                                        <div class="chart">
                                            <canvas id="facebook_donaught"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Audience Growth</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="facebook_bar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div uk-grid class="uk-grid">
                    <div class="uk-width-expand">
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Age</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="facebook_age"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-4">
                        <div class="white-box">                         
                            <div class="white-box-head">
                                <h5>Gender</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="facebook_gender"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div uk-grid class="uk-grid uk-child-width-expand">
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Countries</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody id="countrywiselikegraph">
                                        <!-- <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                         -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Cities</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody id="citywiselikegraph">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Languages</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody id="languageswisegraph">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="grid-tab">
                <div uk-grid class="uk-grid uk-child-width-expand">
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Audience Engagement</h5>
                            </div>
                            <div class="white-box-body">
                                <h6 class="engaged-users">0</h6>
                                <!-- <div class="chart">
                                    <canvas id="engagement_multiline"></canvas>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Engagements</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="engagement_donught"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div uk-grid class="uk-grid">
                    <div class="uk-width-expand">
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Age</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="engagement_agewise"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-4">
                        <div class="white-box">                         
                            <div class="white-box-head">
                                <h5>Gender</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="engagement_gender"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div uk-grid class="uk-grid uk-child-width-expand">
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Countries</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    <img src="https://agencydashboard.io/public/flags/in.png" alt=""> India
                                                </span>
                                            </td>
                                            <td>1,590</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Cities</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                        <tr>
                                            <td>Leaport</td>
                                            <td>1,600</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Languages</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                        <tr>
                                            <td>English (US) (Canada)</td>
                                            <td>1,489</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="grid-tab">
                <div uk-grid class="uk-grid uk-child-width-expand">
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Total Reach</h5>
                            </div>
                            <div class="white-box-body">
                                <h6 class="total-reach">0</h6>
                                <!-- <div class="chart">
                                    <canvas id="reach_multiline"></canvas>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div>
                        <div uk-grid class="uk-grid uk-child-width-expand">
                            <div>
                                <div class="white-box">
                                    <div class="white-box-head">
                                        <h5>Organic vs Paid Likes</h5>
                                    </div>
                                    <div class="white-box-body">
                                        <div class="chart">
                                            <canvas id="reach_likes"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="white-box">
                                    <div class="white-box-head">
                                        <h5>Video Views</h5>
                                    </div>
                                    <div class="white-box-body">
                                        <div class="chart">
                                            <canvas id="reach_video_views"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div uk-grid class="uk-grid">
                    <div class="uk-width-expand">
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Age</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="reach_agewise"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-4">
                        <div class="white-box">                         
                            <div class="white-box-head">
                                <h5>Gender</h5>
                            </div>
                            <div class="white-box-body">
                                <div class="chart">
                                    <canvas id="reach_gender"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div uk-grid class="uk-grid uk-child-width-expand">
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Countries</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody id="countrywisereachgraph">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Cities</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody id="citywisereachgraph">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="white-box">
                            <div class="white-box-head">
                                <h5>Top Languages</h5>
                            </div>
                            <div class="white-box-body table-data">
                                <table>
                                    <tbody id="languagewisereachgraph">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="post-tab">
                <!-- <div class="white-box single">
                    <div class="head">
                        <div class="left">
                            <figure>
                                <img src="https://imark.waveitdigital.com/public/storage/profile_images/ishan-gupta_1648532280.jpg" alt="">
                            </figure>
                            <h6>
                                <span>Subaru Trezia</span>
                                <small>@Subaru Trezia</small>
                            </h6>
                        </div>
                        <p>Jun 10, 2022</p>
                    </div>
                    <div class="media">
                        <img alt="Preview" src="https://scontent.fixc1-5.fna.fbcdn.net/v/t39.30808-6/285203883_3246232632281020_1205570295569187235_n.png?_nc_cat=110&ccb=1-7&_nc_sid=730e14&_nc_ohc=gsNA8orc04cAX8T9DRh&_nc_ht=scontent.fixc1-5.fna&oh=00_AT9hwf6LmYeRnEKBqZVU0vqH1eFL-NfggWMB9h0b1V3OwQ&oe=62CC42F5">
                    </div>
                    <div class="body">
                        <h6>It can be difficult to know where to begin.</h6>
                        <p>The goal of molecular structures is to plant the seeds of passion rather than suffering.</p>
                        <p>science is the nature of non-locality, and of us.</p>
                    </div>
                    <div class="foot">
                        <a class="comments">Comments</a>
                        <ul>
                            <li><span>428</span> Reach</li>
                            <li><span>6</span> Likes</li>
                            <li><span>24</span> Clicks</li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
        <div>
            <div class="reviews-tab">
                <div uk-grid class="uk-grid uk-child-width-1-4 all-reviews">
                    <!-- <div>
                        <div class="white-box single">
                            <p>Jun 11, 2022</p>
                            <h6>
                                <span>Norval Murazik</span>
                                <small>Not Recommended</small>
                            </h6>
                            <div class="review-rating">
                                <i class="fa fa-star active"></i>
                                <i class="fa fa-star active"></i>
                                <i class="fa fa-star active"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="body">
                                <p>Great spot for chilling out.</p>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </ul>
</div>

@endsection