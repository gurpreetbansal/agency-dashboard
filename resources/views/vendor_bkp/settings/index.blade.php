@extends('layouts.vendor_layout')
@section('content')
<style>
.sharedAccImages{
	width:30px;
	height:30px;
	border-radius: 15px;
}
</style>
<div class="app-page-title">
	<div class="page-title-wrapper">
		<div class="page-title-heading">
			<div class="page-title-icon">
				<i class="fa fa-cogs icon-gradient bg-premium-dark"></i>
			</div>
			<div>Settings
				<div class="page-title-subheading">Shared Access</div>
			</div>
		</div>
	</div>
</div>


<div class="tab-content">
	<div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
		<div class="row">
			
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12 table-responsive">
								<form id = "frm_data" enctype="multipart/form-data">
									<input type="hidden" value="{{$user_id}}" name="user_id">
									<table class="table">
										<thead >
											<th>Name</th>
											<th>Email</th>
											<th>Password</th>
											<th>Image</th>
											<th>Restrictions</th>
											<th>Access</th>
											<th></th>
										</thead>
										<tbody id = "tbl_body">
											<input type="hidden" value="" class="errorCount">
											<tr>
												<td><input type = "text" placeholder = "Name" class="form-control " value="{{$master_user->name}}" disabled /></td>
												<td><input type = "text" placeholder = "email@example.com" class="form-control" value="{{$master_user->email}}" disabled /></td>
												<td><input type = "password" placeholder = "Password" class="form-control" value="{{$master_user->password}}" disabled /></td>
												
												@if(!empty($master_user->profile_image))
												<td><img src="{{URL::asset('public/storage/'.$master_user->profile_image)}}"  class="sharedAccImages"></td>
												@else
												<td><img src="{{URL::asset('public/assets/images/no-user-image.png')}}"  class="sharedAccImages"></td>
												@endif
												<td><input type="text" class="form-control" value="Agency Master Account" disabled ></td>
												<td>
													<select class="form-control" name="" disabled>
														<option value="1" selected >Enabled</option>
													</select>
												</td>
												<td><a href="javascript:;"><i class="fa fa-info" data-toggle="tooltip" data-placement="top" data-original-title="Master Access"></i></a></td>
											</tr>

											<?php 
											if(count($shared_accounts) > 0){
												$sharedKey = count($shared_accounts);
											}else{
												$sharedKey = 0;
											}
										
											if(isset($shared_accounts) && !empty($shared_accounts) && count($shared_accounts) > 0){
												foreach($shared_accounts as $key => $account){
											 ?>
											 <input type="hidden" name="shared_id[]" value="{{$account->id}}">
											<tr>
												<td><input type = "text" placeholder = "Name" class="form-control settings_name" autocomplete="off" name="name[]" required value="{{$account->name}}" /></td>
												<td><input type = "email" placeholder = "email@example.com" class="form-control sharedEmail" autocomplete="off" name="email[]" required value="{{$account->email}}" id="email" /><span id="sharedlblError" class="red" ></span></td>
												<td><input type = "password" placeholder = "Password" class="form-control" autocomplete="off" name="password[]" required  value="{{$account->password}}" /></td>
												<td>
													<div class="row">
													<?php 

													if(!empty($account->profile_image)){ ?>
														
														<div class="col-md-3">
														<img src="{{URL::asset('public/storage/'.$account->profile_image)}}" class="sharedAccImages">
														</div>
													<?php } ?>
													<div class="col-md-9">
														<input type="file" name="image[]"  class=" image" accept="image/png,image/jpg,image/jpeg">
													</div>
													</div>
												</td>
												<td>
													<select class="form-control multiselect" multiple name="restrictions[{{$key}}][]" required >
														@if(!empty($projects) && isset($projects))
														@foreach($projects as $project)
															<option value="{{$project->id}}" <?php if (in_array($project->id, explode(',',$account->restrictions))) {
														  echo "selected";
														}?>>{{$project->domain_name}}</option>
														@endforeach
														@endif
													</select>

												</td>
												<td>
													<select class="form-control" name="access[]" required >
														<option value="4" {{ $account->role_id == 4 ? 'selected' : ''}} >View Only(Client)</option>
														<option value="3" {{ $account->role_id == 3 ? 'selected' : ''}} >Addon User (Manager)</option>
													</select>
												</td>
												<td><a href="javascript:;" data-id="{{$account->id}}" class="removeSharedAccess"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" data-original-title="Remove Access"></i></a></td>
											</tr>
											<?php } }  ?>
											<tr>
												<td><input type = "text" placeholder = "Name" class="form-control settings_name" autocomplete="off" name="name[]" required /></td>
												<td><input type = "email" id="email" placeholder = "email@example.com" class="form-control sharedEmail" autocomplete="off" name="email[]" required /><span id="sharedlblError" class="red"></span></td>
												<td><input type = "password" placeholder = "Password" class="form-control" autocomplete="off" name="password[]" required /></td>
												<td><div class="row col-md-12"><input type="file" name="image[]"  class=" image" accept="image/png,image/jpg,image/jpeg"></div></td>
												<td>
													<select class="form-control multiselect" multiple name="restrictions[{{$sharedKey}}][]" required >
														@if(!empty($projects) && isset($projects))
														@foreach($projects as $project)
															<option value="{{$project->id}}">{{$project->domain_name}}</option>
														@endforeach
														@endif
													</select>
												</td>
												<td>
													<select class="form-control" name="access[]" required >
														<option value="4">View Only(Client)</option>
														<option value="3" selected>Addon User (Manager)</option>
													</select>
												</td>
												<td><a href="javascript:;" id="del_row"><i class="fa fa-minus"></i></a></td>
											</tr>
										
										</tbody>
									</table>
									<div class="settings_btn" style="float: right;">
										<button id="addnew" class="btn btn-gradient-info"><i class="fa fa-plus"></i> Add New User</button>
										&nbsp;
										<input type = "submit" value ="Save" id ="sub_button" class="btn btn-gradient-info" />
									</div>
									
									
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection