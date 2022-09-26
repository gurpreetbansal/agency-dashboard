@extends('layouts.main_layout')
@section('content')
<div class="login-page">
    @include('front.outer_left_panel')
    <div class="elem-right">
        <div class="logo">
            <a href="{{url('/')}}">  <img src="{{URL::asset('public/front/img/logo.png')}}" alt="Agency Dashboard Logo"></a>
        </div>
        <div class="form-heading">
            <h3>All-In-One Reporting Platform for Agencies</h3>
            <h4>It only takes a few seconds to create your account</h4>
        </div>
        <div class="login-form">
            <form id='regForm'>
                <input type="hidden" name="package_id" class="package_id" value="{{@$packageId}}">
                <input type="hidden" name="state_value" class="state_value" value="{{@$state_value}}">

                @csrf
                <div id="error_msg" style="color: red;"></div>
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <div class="form-group">
                    <label>Email<span class="asterick text-danger">*</span></label>
                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror regEmail" placeholder="Enter your Email ID" value="{{ old('email')}}" name="email" autocomplete="off" >
                    <span id="lblError"  class="errorStyle"></span>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password<span class="asterick text-danger">*</span></label>
                    <span id="toggle_pwd" class="fa fa-eye-slash"></span>
                    <input name="password" id="password" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror"  autocomplete="off" onkeyup="ValidatePassword();">
                    <span id="lblError1" class="errorStyle"></span>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Company Name<span class="asterick text-danger">*</span></label>
                    <input name="company" id="company" placeholder="Company Name here..." type="text" class="form-control @error('company') is-invalid @enderror" pattern="[0-9a-zA-Z_.-]*">
                    <span id="lblErrorCompany"  class="errorStyle"></span>
                    @error('company')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Vanity Url<span class="asterick text-danger">*</span></label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror vanity-url-field" name="company_name" id="company_name" maxlength="15" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;"
                    onpaste="return false;" onkeyup="this.value=removeSpaces(this.value);">
                    <span class="vanity-url-span">https://</span>
                    <span class="vanity-url-span">.agencydashboard.io</span>
                    <span id="lblErrorCompanyName"  class="errorStyle"></span>
                    <span id="lblErrorCompanyAlpha"  class="errorStyle" style="display: none;">* Special Characters not allowed</span>

                    @error('company_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                @if($packageId != 5)
                <div class="form-group">
                    <label>Coupon</label>
                    <input name="coupon" id="coupon" type="text" class="form-control">
                    <span id="lblErrorCoupon"  class="errorStyle"></span>
                </div>
                @endif

                <div class="d-flex justify-content-between form-group">
                    <div class="text-left custm-btn-group form-group">
                        <input type="button" id="reg_btn" class="btn btn-blue btn-xl" value="Signup">
                    </div>
                    <p>Already have an account? <a href="{{url('/login')}}">Login</a></p>
                </div>

                <div class="text-left">
                    <p>By signing up, you agree to our company's <a href="{{url('/terms-conditions')}}">Terms and Conditions</a> and <a href="{{url('/privacy-policy')}}">Privacy Policy</a></p>
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

<script type="text/javascript">


    function ValidatePassword() {
        var password = document.getElementById("password").value;
        var lblError = document.getElementById("lblError1");
        $('#lblError1.errorStyle').css('display','none');
        lblError.innerHTML = "";
        if(password.length == 0){
           $('#lblError1.errorStyle').css('display','block');
           lblError.innerHTML = "The password field is required.";
       }
       if (password.length < 6) {
           $('#lblError1.errorStyle').css('display','block');
           lblError.innerHTML = "The password must be at least 6 characters.";
       }
   }
   function IsAlphaNumeric(e) {
      var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
      var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <=

        122) || (keyCode == 32));
          // $('#lblErrorCompanyAlpha.errorStyle').css('display','block');
          document.getElementById("lblErrorCompanyAlpha").style.display = ret ? "none" : "inline";
          return ret;
      }
function removeSpaces(string){
   return string.split(' ').join('');
}
   
</script>
@endsection