@extends('layouts.admin_internal_pages')
@section('content')
<style>
	.invalid-feedback{
		color: red;
		font-size:10px;
	}
	.is-invalid {
		border-color: red;
	}
</style>
<div class=" h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-12">
	<div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
		<div>
			<div class="white-box pa-0">
				<div class="white-box-head">
					<div class="left">
						<div class="heading">
							<img src="{{URL::asset('public/vendor/internal-pages/images/add.png')}}">
							<h2>Add</h2>
						</div>
					</div>
					<div class="right">
				<div class="heading ajax-loader">
					<a href="{{url('/admin/site-audit')}}"><button class="btn blue-btn">Back</button></a>
				</div>
			</div>
				</div>
				<div class="white-box-body">
					<form  enctype="multipart/form-data" autocomplete="off"  action="{{ url('/admin/site-audit/store') }}"  method="POST">
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
							<div class="form-group">
								<label>Category</label>
								<select class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category') }}">
									<option value="">-Select-</option>
									<option value="1">Critical</option>
									<option value="2">Warning</option>
									<option value="3">Notices</option>
								</select>
								@error('category')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>

							<div class="form-group">
								<label>Error Key</label>
								<input type="text" class="form-control @error('error_key') is-invalid @enderror" value="{{ old('error_key') }}" placeholder="Error Key" name="error_key" autocomplete="off">
								@error('error_key')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>

							<div class="form-group">
								<label>Error Label</label>
								<input type="text" class="form-control @error('error_label') is-invalid @enderror" value="{{ old('error_label') }}" placeholder="Error Label" name="error_label" autocomplete="off">
								@error('error_label')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>



							<div class="form-group">
								<label>Short Description</label>
								<textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description') }}</textarea>
								@error('short_description')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>

							<div class="form-group">
								<label>Description</label>
								<textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ old('description') }}</textarea>
								@error('description')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>

						</div>
						<div class="uk-text-right">
							<button type="submit" class="btn blue-btn">Add</button>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection