@extends('layouts.admin')
@section('content')
<div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-12">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
        <h3 class="mb-0">
            <span class="d-block">Change Your Password</span>
        </h3>

        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ url('/admin/changepassword') }}">
                @csrf


                @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                <div class="form-row">
                    <div class="col-md-9">
                        <div class="position-relative form-group">
                            <label for="examplePassword" class="">Current Password</label>
                            <input name="current_password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="position-relative form-group">
                            <label for="examplePassword" class="">New Password</label>
                            <input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="position-relative form-group">
                            <label for="examplePassword" class="">Confirm Password</label>
                            <input name="confirm_password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('confirm_password') is-invalid @enderror">
                            @error('confirm_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="divider row"></div>
                <div class="d-flex align-items-center">
                    <div class="ml-auto">
                        <button class="btn btn-primary btn-lg">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection          