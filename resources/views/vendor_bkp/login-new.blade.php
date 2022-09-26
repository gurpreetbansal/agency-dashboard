@extends('layouts.main_layout')
@section('content')
<div class="login-page">
        @include('front.outer_left_panel')
        <div class="elem-right">
            <div class="logo">
                <a href="{{url('/')}}"><img src="{{URL::asset('public/front/img/logo.png')}}" alt="Agency Dashboard Logo"></a>
            </div>
            <div class="form-heading">
                <h3>All-In-One Reporting Platform for Agencies</h3>
                <h4>Welcome <?php if(!empty($domain_name) && ($domain_name != null)){ echo '<b class="login_domainName">'.$domain_name.'</b>';  } else{ echo 'Back';} ?> ! Please Login to your account to continue</h4>
            </div>
            <div class="login-form">
                <form  method="POST" action="{{ url('/doLoginNew') }}">
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
                                  <input id="exampleCheck" type="checkbox" class="form-check-input" name="remember"  {{ old('remember') ? 'checked' : '' }} value="1" checked>
                                <span class="custom-checkbox"></span>
                                Keep me logged in
                            </label>
                        </div>

                        <a href="{{url('/recover-password')}}">Recover Password</a>
                    </div>

                    <div class="text-left custm-btn-group form-group">
                        <input type="submit" class="btn btn-blue btn-xl" value="Login">
                        <a href="{{url('/price')}}" class="btn btn-transparent btn-border btn-xl">Signup</a>
                    </div>
                </form>
            </div>
            <div class="form-footer">
                <h4>We are launching Soon:</h4>
                <a href="javascript::void()"><i class="fa fa-apple"></i></a>
                <a href="javascript::void()"><i class="fa fa-android"></i></a>
            </div>

        </div>
    </div>
    @endsection