@extends('layouts.vendor_layout')
@section('content')
<div class="card mb-4">
	<div class="card-header">
		<div class="media flex-wrap w-100 align-items-center">

			<div class="media-body ml-3">
				<a href="javascript:void(0)">Account Settings</a>
			</div>


		</div>
	</div>
	<div class="card-body">
		<div  class="">

			<form class="col-xl-9 col-sm-12 form-horizontal" id="account_settings">
				<input type="hidden" name="request_id" value="{{\Request::segment(2)}}">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-4">
							<label>Cancel Subscription &nbsp;
								<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" title="This will cancel your existing Subscription, all the services will be stopped!"></i></label>
						</div>

						<div class="col-sm-8">
							<div class="custom-control custom-switch">
								<input name="cancel_subscription" type="checkbox" class="custom-control-input btn btn-primary" id="cancel_subscription" >

								<label class="custom-control-label" for="cancel_subscription"></label>
							</div>
						</div>
					</div>
				</div>

			</form>

		</div>

	</div>
</div>

</div>
@endsection