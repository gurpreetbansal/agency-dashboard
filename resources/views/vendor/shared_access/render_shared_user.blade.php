<div class="white-box-head">
    <div class="left">
        <div class="heading">
            <img src="{{URL::asset('public/vendor/internal-pages/images/edit_icon.png')}}">
            <h2>Edit User</h2>
        </div>
    </div>
            <div class="update-sharedAccess-progress-loader progress-loader"></div>
</div>
<div class="white-box-body">
    <form id="shared_access_form_edit" enctype="multipart/form-data" action="{{ url('/ajax_update_existing_shared_user' ) }}" method="post">
       @csrf
        <span id="overall_errors_edit" class="error errorStyle"></span>
        <input type="hidden" value="{{$user_id}}" name="user_id" class="shared_user_id">
            <div class="field-group center" id="projectlogo-img-section">
                <div class="form-group file-group">
                    <label>User Image</label>
                    <label class="custom-file-label">
                        <input type="file" name="profile_image" id="update_shared_access_image" accept="image/png,image/jpg,image/jpeg" class="shared_access_image">
                        <div class="custom-file form-control <?php if(isset($shared_accounts) && (isset($shared_accounts->profile_image) && !empty($shared_accounts->profile_image))){ echo 'selected';}?>"  id="custom-div-updateShared">
                            <span uk-icon="icon:  upload"></span>
                            <span uk-icon="icon:  pencil" class="edit"></span>
                            <span id="fileName" class="fileName">User Image</span>
                            <span>Choose a file or drag it here.</span>
                            <div class="uploaded-file" id="img-update-profileImage">
                                @if(isset($shared_accounts->profile_image) && !empty($shared_accounts->profile_image))
                                <img id="shared_access_update_preview" src="{{$shared_accounts->profile_image}}" alt="profile-img" >
                                @else
                                <img id="shared_access_update_preview"  alt="profile-img" >
                                @endif
                            </div>
                        </div>
                    </label>
                </div>
                <div class="elem-right text-right">
                     <span class="errorStyle error"><p id="shared_access_image_error"></p></span>
                </div>
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>Name</label>
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></span>
                    <input type="text" class="form-control shared_access_name" placeholder="Name" name="name" autocomplete="off" value="{{@$shared_accounts->name}}">
                    <span id="shared_access_name_error" class="error errorStyle"></span>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}"></span>
                    <input type="email" class="form-control shared_access_email" placeholder="Email Address" name="email" autocomplete="off" value="{{@$shared_accounts->email}}">
                    <span id="shared_access_email_error" class="error errorStyle"></span>
                </div>

                <div class="form-group dropdown">
                    <label>Select Projects</label>
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                    <select class="selectpicker shared_access_edit_projects" multiple data-live-search="true" id="shared_access_edit_projects_append">
                        <option value="">Select Projects</option>
                        @if(!empty($projects) && isset($projects))
                        @foreach($projects as $project)
                        <option value="{{$project->id}}" <?php if (in_array($project->id, explode(',',$shared_accounts->restrictions))) { echo "selected";}?> <?php if($shared_accounts->role_id == 3){ if($project->get_assigned_projects_taken($project->id,$shared_accounts->id) == 1){ echo "disabled class='disabled-project-shared'"; } } ?>>{{$project->domain_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <input type="hidden" id="shared_edit_selected_id" value="{{$shared_accounts->restrictions}}" name="shared_edit_selected_id">
                    <span id="shared_access_projects_error" class="error errorStyle"></span>
                </div>

                <div class="form-group">
                    <label>Access Type</label>
                    <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                    <select class="form-control  shared_access_access_type selectpicker" name="access_type" id="shared_existingUser_accessType">
                        <option value="4" {{ $shared_accounts->role_id == 4 ? 'selected' : ''}} >View Only(Client)</option>
                        <option value="3" {{ $shared_accounts->role_id == 3 ? 'selected' : ''}} >Addon User (Manager)</option>
                    </select>
                </div>

            </div>
        <div class="uk-text-right">
            <button type="button" class="btn btn-border" value="Cancel" id="edit-user-cancel">Cancel</button>
            <button type="submit" disabled class="btn blue-btn" id="edit_user_access"><i class="fa fa-pencil" aria-hidden="true"></i> Update</button>
        </div>
    </form>
</div>