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
                <button type="button" id="monthly" class="active getActiveState">monthly</button>
                <button type="button" id="yearly" class="getActiveState">yearly</button>
                <span class="pointer"></span>
            </div>
        </div>

        <form name="price">
            <input type="hidden" name="user_id" value="{{@Auth::user()->id}}" class="user_id" />
            @csrf
            <div class="pricing-box-cover">
               @if(isset($packages) && !empty($packages))
               @foreach($packages as $key=>$value)
               <div class="pricing-box {{strtolower(str_replace(' ', '', $value->name))}} <?php if($value->name == 'Agency'){ echo 'recommended';}?>">
                <div class="pricing-box-head">
                    <h3>{{$value->name}}</h3>
                    <h6 class="monthly-price"><big><sup>$</sup>{{$value->monthly_amount}}</big>/mo</h6>
                    <h6 class="yearly-price"><big><sup>$</sup>{{$value->yearly_amount}}</big>/mo</h6>
                </div>
                <div class="pricing-box-main">
                    <ul>
                        <li data-toggle="tooltip" data-placement="left" title="Each campaign represents one of your clients">
                            <strong>{{$value->number_of_projects}}</strong> Campaigns
                        </li>
                        <li data-toggle="tooltip" data-placement="left" title="One keyword counts towards all of the ranking sources your account has enabled">
                            <strong>{{$value->number_of_keywords}}</strong> Keyword Rankings
                        </li>
                    </ul>
                    @if(isset($user_package->package_id))
                        @if($value->id < @$user_package->package_id)
                        <a href="javascript:;" ><button type="button" class="btn pricing-btn pricing-downgrade" data-amount="{{$value->id}}" data-state="month">Downgrade</button></a>
                        @elseif($value->id == @$user_package->package_id)
                            @if($user->subscription_status == 1)
                            <a href="javascript:;" ><button type="button" class="btn pricing-btn" disabled>Your Plan</button></a>
                            @else
                            <a href="javascript:;" ><button type="button" class="btn pricing-btn pricing-action" data-amount="{{$value->id}}" data-state="month">Your Plan</button></a>
                            @endif
                        @else
                        <a href="javascript:;" ><button type="button" class="btn pricing-btn pricing-action" data-amount="{{$value->id}}" data-state="month">Upgrade</button></a>
                        @endif
                    @else
                    <a href="javascript:;" ><button type="button" class="btn pricing-btn pricing-action" data-amount="{{$value->id}}" data-state="month">Start Trial</button></a>
                    @endif
                    
                </div>
                @if(count($value->package_feature) > 0)
                <div class="pricing-box-features-list">
                    <h4>Features Included</h4>
                    <ul>
                        @foreach($value->package_feature as $feature)
                        <li data-toggle="tooltip" data-placement="left" title="{{$feature->feature}}">{{$feature->feature}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
            @endif



            <input type="hidden" id="price" name="package_id"/>
            <input type="hidden" id="state" name="state_value"/>
        </div>
    </form>
</div>


<div class="shape1 rellax" data-rellax-speed="3">
    <img src="{{URL::asset('public/front/img/shape-1.png')}}">
</div>



</section>
@endsection