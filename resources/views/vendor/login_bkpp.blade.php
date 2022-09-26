@extends('layouts.main_layout')
@section('content')

<div class="login-page">
        <div class="elem-left" style="background-image: url('public/front/img/login-bg.jpg');">
            <div class="post-with-icon-cover">

                <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                    <figure>
                        <img src="{{URL::asset('public/front/img/seo-icon.png')}}" alt="SEO">
                    </figure>
                    <h4>SEO</h4>
                    <p>Nullam sit amet mauris
                        sagittis sem eu.</p>
                </div>

                <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                    <figure>
                        <img src="{{URL::asset('public/front/img/ppc-icon.png')}}" alt="PPC">
                    </figure>
                    <h4>Pay Per Click</h4>
                    <p>Nullam sit amet mauris
                        sagittis sem eu.</p>
                </div>

                <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                    <figure>
                        <img src="{{URL::asset('public/front/img/smm-icon.png')}}" alt="SMM">
                    </figure>
                    <h4>Social media
                        marketing</h4>
                    <p>Nullam sit amet mauris
                        sagittis sem eu.</p>
                </div>

                <div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
                    <figure>
                        <img src="{{URL::asset('public/front/img/my-business-icon.png')}}" alt="My business">
                    </figure>
                    <h4>My business</h4>
                    <p>Nullam sit amet mauris
                        sagittis sem eu.</p>
                </div>

            </div>

            <div class="text-center">
                <h2><strong>Manage All Your Dashboards</strong> Under One Roof</h2>
                <a href="#" class="btn btn-blue btn-xl">Know More</a>
            </div>

        </div>
        <div class="elem-right">
            <div class="logo">
                <img src="{{URL::asset('public/front/img/logo.png')}}" alt="Agency Dashboard Logo">
            </div>
            <div class="form-heading">
                <h3>All-In-One Reporting Platform for Agencies</h3>
                <h4>Welcome <?php if(!empty($domain_name) && ($domain_name != null)){ echo '<b class="login_domainName">'.$domain_name.'</b>';  } else{ echo 'Back';} ?> ! Please Login to your account to continue</h4>
            </div>
            <div class="login-form">
                <form  method="POST" action="{{ url('/doLogin') }}">
                     @csrf

                     @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your Email ID" value="{{ old('email')}}" name="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between form-group">
                        <div class="checkbox-group">
                            <label>
                                  <input id="exampleCheck" type="checkbox" class="form-check-input" name="remember"  {{ old('remember') ? 'checked' : '' }}>
                                <span class="custom-checkbox"></span>
                                Keep me logged in
                            </label>
                        </div>

                        <a href="#">Recover Password</a>
                    </div>

                    <div class="text-left custm-btn-group form-group">
                        <input type="submit" class="btn btn-blue btn-xl" value="Login">
                        <a href="{{\env('BASE_URL').'register'}}" class="btn btn-transparent btn-border btn-xl">Signup</a>
                    </div>

                    <div class="text-left">
                        <p>By signing up, you agree to our company's <a href="{{url('/privacy-policy')}}">Terms and Conditions</a> and <a href="{{url('/privacy-policy')}}">Privacy Policy</a></p>
                    </div>
                </form>
            </div>
            <div class="form-footer">
                <h4>We are launching Soon:</h4>
                <a href="#"><i class="fa fa-apple"></i></a>
                <a href="#"><i class="fa fa-android"></i></a>
            </div>

        </div>
    </div>
    @endsection