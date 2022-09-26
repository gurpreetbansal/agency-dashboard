@extends('layouts.vendor_layout')
@section('content')

<div class="profile_page h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-12">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
        <h3 class="mb-0">
            <span class="d-block">Profile</span>
        </h3>

        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ url('/updateprofile') }}" enctype="multipart/form-data" />
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

                <div class="col-md-6">
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
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="company_name" class="">Company Name</label>
                        <input name="company_name" id="company_name" placeholder="Company Name" type="text" class="form-control @error('company_name') is-invalid @enderror changedisable" value="{{$user->company_name}}"disabled="">
                        @error('company_name')
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


                <div class="col-md-9">
                    <div class="position-relative form-group">
                        <label for="address1" class="">Address Line 1</label>
                        <input name="address_line_1"  type="text" class="form-control @error('address_line_1') is-invalid @enderror" placeholder="1234 Main St" value="{{@$user->UserAddress->address_line_1}}"> 
                        @error('address_line_1')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-9">
                    <div class="position-relative form-group">
                        <label for="address_line_2" class="">Address Line 2</label>
                        <input name="address_line_2"  type="text" class="form-control" placeholder="Apartment, studio, or floor" value="{{@$user->UserAddress->address_line_2}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="city" class="">City</label>
                        <input name="city"  type="text" class="form-control @error('city') is-invalid @enderror" value="{{@$user->UserAddress->city}}">
                        @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="country" class="">Country</label>
                        <select name="country" class="form-control @error('country') is-invalid @enderror">
                            <option value="">-Select-</option>
                            <?php
                            if (!empty($countries)) {
                                foreach ($countries as $country) {
                                    ?>
                            <option value="{{$country->id}}" {{$country->id==@$user->UserAddress->country?'selected':''}}>{{$country->countries_name}}</option>
                                <?php
                                }
                            }
                            ?>
                        </select>

                        @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="position-relative form-group">
                        <label for="zip" class="">Zip</label>
                        <input name="zip"  type="text" class="form-control @error('zip') is-invalid @enderror" maxlength="6" onkeypress='return restrictAlphabets(event)' value="{{@$user->UserAddress->zip}}">
                        @error('zip')
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