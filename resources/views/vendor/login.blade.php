@extends('layouts.vendor')
@section('content')


<style type="text/css">
    b.login_domainName {
    color: #3f6ad8;
}
</style>
<div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-7">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
        <div class="app-logo"></div>
        <h4 class="mb-0">
            <?php 
// dd($domain_name);
            ?>
            <span class="d-block">Welcome 
                <?php if(!empty($domain_name) && ($domain_name != null)){ echo '<b class="login_domainName">'.$domain_name.'</b>';  } else{ echo 'Back';} ?>,
            </span>
            <span>Please sign in to your account.</span>
        </h4>
        <!--<h6 class="mt-3">No account? <a href="javascript:void(0);" class="text-primary">Sign up now</a></h6>-->
        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ url('/doLogin') }}">
                @csrf

                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                 @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">Email</label>
                            <input name="email" id="exampleEmail" placeholder="Email here..." type="email" class="form-control @error('email') is-invalid @enderror"  value="{{ old('email')}}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="examplePassword" class="">Password</label>
                            <input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="position-relative form-check">
                    <input id="exampleCheck" type="checkbox" class="form-check-input" name="remember"  {{ old('remember') ? 'checked' : '' }}>
                           <label for="exampleCheck" class="form-check-label">Keep me logged in</label>
                </div>
                <div class="divider row"></div>
                <div class="d-flex align-items-center">
                    <div class="ml-auto">

                        <!-- <a href="{{url('/admin/forgot_password')}}" class="btn-lg btn btn-link">Recover Password</a> -->

                        <button class="btn btn-primary btn-lg">Login to Dashboard</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection          