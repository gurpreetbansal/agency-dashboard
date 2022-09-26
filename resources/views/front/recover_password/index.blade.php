@extends('layouts.main_layout')
@section('content')
<div class="login-page">
    @include('front.outer_left_panel')
    <div class="elem-right">
        <div class="logo">
           <a href="{{url('/')}}">   <img src="{{URL::asset('public/front/img/logo.png')}}" alt="Agency Dashboard Logo"></a>
        </div>
        <div class="form-heading">
            <h3>Recover your password</h3>
        </div>
        <div class="login-form">
           @if (session('status'))
           <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-warning" role="alert">
            {{ session('error') }}
        </div>
        @endif
        <form  method="POST" action="{{ url('/post_recover_password') }}">
         @csrf
         <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your Email ID" value="{{ old('email')}}" name="email">
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="d-flex justify-content-between form-group">
            <div class="text-left custm-btn-group form-group">
                <input type="submit" class="btn btn-blue btn-xl" value="Recover Password">
            </div>
            <p><a href="{{url('/login')}}" class="back_to_login">Back to Login</a></p>
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
<script>
    var ifClassExists = document.getElementsByClassName('alert-success');
    if (ifClassExists.length > 0) {
     setTimeout(function () {
        value = $('.back_to_login').attr('href');
        window.location.href= value; 
    },5000); 
 }
</script>
@endsection