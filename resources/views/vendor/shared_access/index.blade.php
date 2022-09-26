@extends('layouts.vendor_internal_pages')
@section('content')

<div>
    <div class="white-box pa-0 mb-40">
        <div class="white-box-head">
           <div class="left">
            <div class="heading">
             <img src="{{URL::asset('public/vendor/internal-pages/images/profile-icon.png')}}">
             <h2>Shared Access Settings</h2>
         </div>
     </div>
     <div class="right">
        <a href="javascript:void();" class="btn blue-btn btn-sm" id="AddNewUserBtn"><span uk-icon="icon: user" class="uk-icon"></span><span uk-icon="icon: plus" class="uk-icon" style="width: 10px;height: 10px;margin: 0;position: relative;top: -4px;left: -2px;"></span> Add User</a>
    </div>
</div>
<div class="white-box-body no-flex">
    <div class="shared-access-list">
        <table class="border">
            <tr>
                <th>Profile Pic</th>
                <th>Name</th>
                <th>Project</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            @if(isset($shared_accounts) && (count($shared_accounts) > 0))
            @foreach($shared_accounts as $account)
            <tr>
                <td>
                    @if(isset($account->profile_image) && !empty($account->profile_image))
                    <figure>
                        <img src="{{$account->profile_image}}" alt="profile_image">
                    </figure>
                    @else
                    <figure class="{{$account->initial_background}}">
                        <?php
                            $words = explode(' ', $account->name);
                            $initial =  strtoupper(substr($words[0], 0, 1));
                        ?>
                        <figcaption>{{$initial}}</figcaption>
                    </figure>
                    @endif
                </td>
                <td>{{$account->name }}
                    <span><?php echo '('.$account->get_user_role_name($account->role_id).')';?></span>
                </td>
                <td>
                    <button type="button" class="btn btn-border blue-btn-border btn-sm">List of projects</button>
                    <div uk-dropdown="pos: right-center" class="uk-dropdown">
                        <ul class="uk-nav uk-dropdown-nav">
                            <?php
                            $restrictions = $account->get_restricted_projects($account->restrictions);
                            if(!empty($restrictions) && isset($restrictions)){
                                foreach($restrictions as $restrict){
                                    ?>
                                    <li><a href="javascript:;">{{$restrict->domain_name}}</a></li>
                                <?php } } ?>
                            </ul>
                        </div>
                    </td>
                    <td>{{$account->email}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="javascript:;" class="btn small-btn icon-btn color-blue update_shared_access" id="EditExistingUserBtn" data-id="{{$account->id}}" uk-tooltip="title:Edit Details; pos: top-center" >
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="javascript:;" class="btn small-btn icon-btn color-red delete_shared_access" data-id="{{$account->id}}" uk-tooltip="title:Delete User; pos: top-center" >
                                <img src="{{URL::asset('public/vendor/internal-pages/images/delete-icon-small.png')}}">
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                 <tr><td colspan="5"><center>No User added yet</center></td></tr>
                @endif
            </table>
        </div>

        <div class="shared-access-box white-box pa-0" id="AddNewUserBox">
            <div class="white-box-head">
                <div class="left">
                    <div class="heading">
                        <img src="{{URL::asset('public/vendor/internal-pages/images/add.png')}}">
                        <h2>Add New User</h2>
                    </div>
                </div>
                        <div class="add-sharedAccess-progress-loader progress-loader"></div>
            </div>
            <div class="white-box-body">
                <form id="shared_access_form" enctype="multipart/form-data" autocomplete="off">
                     @csrf
                    <span id="overall_errors" class="error errorStyle"></span>
                    <input type="hidden" value="{{$user_id}}" name="user_id">

                    <div class="field-group center" id="projectlogo-img-section">
                        <div class="form-group file-group">
                            <label>User Image</label>
                            <label class="custom-file-label">
                                <input type="file" name="profile_image" id="shared_access_image" accept="image/png,image/jpg,image/jpeg" class="shared_access_image">
                                <div class="custom-file form-control" id="custom-div-addShared">
                                    <span uk-icon="icon:  upload"></span>
                                    <span uk-icon="icon:  pencil" class="edit"></span>
                                    <span id="fileName" class="fileName">User Image</span>
                                    <span>Choose a file or drag it here.</span>
                                    <div class="uploaded-file" id="img-add-profileImage">
                                        <img id="shared_access_add_preview"  alt="profile-img" >
                                    </div>
                                </div>
                            </label>

                        </div>

                        <div class="elem-right text-right">
                             <span class="errorStyle error"><p id="profile_image_error"></p></span>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label>Name</label>
                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></span>
                            <input type="text" class="form-control shared_access_new_user_name" placeholder="Name" name="name" autocomplete="off" value="">
                            <span id="shared_name_error" class="error errorStyle"></span>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}"></span>
                            <input type="email" class="form-control shared_access_new_user_email" placeholder="Email Address" name="email" autocomplete="off" value="">
                            <span id="shared_email_error" class="error errorStyle"></span>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}"></span>
                            <input type="password" class="form-control shared_access_new_user_password" placeholder="Password" name="password" autocomplete="off" value="">
                            <span id="shared_password_error" class="error errorStyle"></span>
                        </div>

                        <div class="form-group dropdown">
                            <label>Select Projects</label>
                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-property-icon.png')}}"></span>
                            <select class="selectpicker  shared_access_new_user_projects" multiple data-live-search="true" name="shared_access_new_user_projects[]" id="shared_access_new_user_projects_append">
                                <option value="">Select Projects</option>
                                @if(!empty($projects) && isset($projects))
                                @foreach($projects as $project)
                                <option value="{{$project->id}}" <?php //if($project->get_assigned_projects($project->id) == 1){ echo "disabled class='disabled-project-shared'"; }?>>{{$project->domain_name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <input type="hidden" id="shared_selected_id">
                            <span id="shared_projects_error" class="error errorStyle"></span>
                        </div>

                        <div class="form-group">
                            <label>Access Type</label>
                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/select-account-icon.png')}}"></span>
                            <select class="form-control  shared_access_new_user_access_type" name="shared_access_new_user_access_type" id="shared_newUser_accessType">
                                <option value="4">View Only (Client)</option>
                                <option value="3">Addon User (Manager)</option>
                            </select>
                        </div>

                    </div>
                    <div class="uk-text-right">
                        <button type="button" class="btn btn-border" value="Cancel" id="add-user-cancel">Cancel</button>
                        <button type="submit" disabled class="btn blue-btn" id="create_new_user_access"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Invite</button>
                    </div>
                </form>
            </div>

        </div>

        <div class="shared-access-box white-box pa-0" id="editExistingUserBox">

        </div>
    </div>
</div>
</div>

@endsection