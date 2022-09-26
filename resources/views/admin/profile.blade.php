@extends('layouts.admin')
@section('content')
<style>
    .changedisable{
        background-color:#fff !important;
    }
</style>
<div class="profile_page h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-12">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
        <h3 class="mb-0">
            <span class="d-block">Profile</span>
        </h3>

        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ url('/admin/updateprofile') }}" enctype="multipart/form-data" />
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
                        <label for="name" class="">Name</label>
                        <input name="name" id="name" placeholder="Full Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{$user->name}}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="position-relative form-group">
                        <label for="name" class="">Email</label>
                        <input name="email" id="name" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror changedisable" value="{{$user->email}}" disabled="">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
               
                
                 <div class="col-md-9">
                    <div class="position-relative form-group">
                        <label for="name" class="">Phone</label>
                        <input name="phone" id="phone" placeholder="Phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{$user->phone}}" onkeypress='return restrictAlphabets(event)' maxlength="10">
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="position-relative form-group">
                        <label for="profile_image" class="">Profile Image</label>
                        <input name="profile_image"  type="file" class="form-control @error('profile_image') is-invalid @enderror">
                        @error('profile_image')
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
                    <button class="btn btn-primary btn-lg">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function restrictAlphabets(e) {
        var x = e.which || e.keycode;
        if ((x >= 48 && x <= 57))
            return true;
        else
            return false;
    }
</script>
@endsection  