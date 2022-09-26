@extends('layouts.main_layout')
@section('content')

@if(Auth::user() <> null)
<section class="pricing-section pt" @if(Auth::user()->role_id == 4) style="pointer-events:none;" @endif>
  @if(Auth::user()->role_id == 4)
  <div class="overlay-content">
    <div class="inner">
        <p>You're already logged in as a client under {{ucfirst(Auth::user()->company_name)}} agency.</p>
        <p>Logout & sign-up from a different e-mail address.</p>
    </div>
</div>
@elseif(Auth::user()->role_id == 3)
<div class="overlay-content">
    <div class="inner">
        <p>You're already logged in as a manager under {{ucfirst(Auth::user()->company_name)}} agency.</p>
        <p>Logout & sign-up from a different e-mail address.</p>
    </div>
</div>
@endif
@else
<section class="pricing-section pt">
    @endif
    <span id="comparePlans" class="blankSpace"></span>

    <div class="container">
        <div class="text-center">
            <h1><strong>Pricing & Packages </strong></h1>
            <h5>Tailored for agencies - big & small, enterprises, and independent digital marketers.</h5>
            <p>Try Agency Dashboard free for 14 days.</p>
            <div class="pricing-tab">
                <button type="button" id="monthly" class="active getActiveState" data-attr="monthly">monthly</button>
                <button type="button" id="yearly" class="getActiveState" data-attr="yearly">yearly</button>
                <span class="pointer"></span>
            </div>
        </div>

        <form name="price">
            <input type="hidden" name="user_id" value="{{@Auth::user()->id}}" class="user_id" />
            @csrf
            <div class="pricing-box-cover">
                @if(isset($packages) && !empty($packages))
                @foreach($packages as $key=>$value)
                <div
                class="pricing-box {{strtolower(str_replace(' ', '', $value->name))}} <?php if($value->name == 'Agency'){ echo 'recommended';}?>">
                <div class="pricing-box-head">
                    <h3>{{$value->name}}</h3>
                    <h6 class="monthly-price"><big><sup>$</sup>{{$value->monthly_amount}}</big>/mo</h6>
                    <h6 class="yearly-price"><big><sup>$</sup>{{$value->yearly_amount}}</big>/mo</h6>
                </div>
                <div class="pricing-box-main">
                    <ul>
                        <li data-toggle="tooltip" data-placement="left"
                        title="Each campaign represents one of your clients">
                        <strong>{{$value->number_of_projects}}</strong> Campaigns
                    </li>
                    <li data-toggle="tooltip" data-placement="left"
                    title="One keyword counts towards all of the ranking sources your account has enabled">
                    <strong>{{$value->number_of_keywords}}</strong> Keyword Rankings
                </li>
            </ul>

            @if(isset($user_package->package_id) &&  $user->user_type !== 1)
            @if($value->id < @$user_package->package_id)
            <a href="javascript:;"><button type="button" class="btn pricing-btn pricing-downgrade" data-amount="{{$value->id}}" data-state="month">Downgrade</button></a>
            @elseif($value->id == @$user_package->package_id)
            @if($user->subscription_status == 1)
            <a href="javascript:;" id="CurrentPlan"><button type="button" class="btn pricing-btn" disabled>Current Plan</button></a>
            <a href="javascript:;" id="SelectPlan" style="display: none;"><button type="button" class="btn pricing-btn take-to-plan" data-amount="{{$value->id}}" data-state="month">Select Plan</button></a>
            @elseif($user->subscription_status == 0)
            <a href="javascript:;" id="SelectPlan"><button type="button" class="btn pricing-btn take-to-plan" data-amount="{{$value->id}}" data-state="month">Select Plan</button></a>
            @endif
            @else
            @if($value->id == 9)
            <a href="javascript:;"><button type="button" class="btn pricing-btn take-to-plan" data-amount="{{$value->id}}" data-state="month">Upgrade</button></a>
            @else
            <a href="javascript:;"><button type="button" class="btn pricing-btn take-to-plan" data-amount="{{$value->id}}" data-state="month">Upgrade</button></a>
            @endif
            @endif
            @elseif(isset($user_package->package_id) &&  $user->user_type == 1)
            <a href="javascript:;"><button type="button" class="btn pricing-btn take-to-plan" data-amount="{{$value->id}}" data-state="month">Upgrade</button></a>
            @else
            <a href="javascript:;"><button type="button" class="btn pricing-btn pricing-action" data-amount="{{$value->id}}" data-state="month">Start Trial</button></a>
            @endif

        </div>
        @if(count($value->package_feature) > 0)
        <div class="pricing-box-features-list">
            <h4>Features Included</h4>
            <ul>
                @foreach($value->package_feature as $feature)
                <li data-toggle="tooltip" data-placement="left"
                title="<?php if($feature->tooltip_title == null){ echo $feature->feature;}else{ echo $feature->tooltip_title;}?>">
                {{$feature->feature}}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endforeach
    @endif



    <input type="hidden" id="price" name="package_id" />
    <input type="hidden" id="state" name="state_value" />
</div>
</form>


<!-- free-forever -->
<div class="free-price">
   <div class="left">
      <h3>Free Forever</h3>
        <ul>
            <li data-toggle="tooltip" data-placement="left" title="Each campaign represents one of your clients">1 Campaigns</li>
            <li data-toggle="tooltip" data-placement="left" title="One keyword counts towards all of the ranking sources your account has enabled">50 Keyword Rankings</li>
        </ul>
        <p>For startup & Small business</p>
        
        @if(isset($user) && !empty($user))
                @if($user->user_type == 1)
                    @if($user->subscription_status == 1)
                       <a href="javascript:;"><button type="button" class="btn pricing-btn" disabled>Current Plan</button></a>
                        @else
                       <a href="javascript:;"><button type="button" class="btn pricing-btn pricing-action freeForever" data-amount="5" data-state="free">Current Plan</button></a>
                    @endif
                @else
                <a href="javascript:;"><button type="button" class="btn pricing-btn pricing-downgrade freeForever" data-amount="5" data-state="free">Downgrade</button></a>
                @endif
        @else
        <a href="javascript:;"><button type="button" class="btn pricing-btn pricing-action freeForever" data-amount="5" data-state="free">Start Now</button></a>
        @endif
    </div>
    <div class="right">
      <h6>Features included</h6>
       <div class="d-flex">
            <ul>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="The ability to track mobile rankings in Position tracking tool.">Mobile rankings</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="The total number of keywords that can be tracked for all your projects simultaneously using the Position Tracking tool.">Daily Rank Tracking</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="Competitive and Keyword Research">Competitive and Keyword Research</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="The maximum number of pages you can crawl for your Site Audit campaign per audit.">50 Pages to crawl per project</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="Choose from the top SEO, PPC, GMB, Analytics, and other Integrations">Access to numerous Integrations</li>
            </ul>
            <ul>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="Schedule unlimited number of reports to be automatically generated and sent in PDF format to multiple email addresses on a regular basis.">Scheduled PDF reports</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="Additional add-ons credits to each plan.">500 Additional Website Audit report credits</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="SEO Dashboard">SEO Dashboard</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="Google Ads Dashboard">Google Ads Dashboard</li>
               <li data-toggle="tooltip" data-placement="left" title="" data-original-title="GMB Dashboard">GMB Dashboard</li>
            </ul>
        </div>
    </div>
</div>
<!-- end free-forever -->


</div>

<div class="shape1 rellax" data-rellax-speed="3">
    <img src="{{URL::asset('public/front/img/shape-1.png')}}">
</div>
</section>
@endsection