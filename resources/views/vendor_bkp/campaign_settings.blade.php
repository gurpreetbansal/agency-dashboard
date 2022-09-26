@extends('layouts.vendor_layout',['page' => 'campaigndetail'])
@section('content')
 <div class="card mb-4">
        <div class="card-header">
            <div class="media flex-wrap w-100 align-items-center">

                <div class="media-body ml-3">
                    <a href="javascript:void(0)">Settings</a>
                </div>
                <div class="right">
                  <a href="{{url('/new-dashboard/'.\Request::segment(2))}}"><button class="mb-2 mr-2 btn btn-gradient-info"><i class="fa fa-arrow-circle-left"></i> BACK</button></a>
                </div>

            </div>
        </div>
		<input type="hidden" class="campaignId" value="{{\Request::segment(2)}}">
        <div class="card-body">
          <div class="settings-tab">
				
				<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav ">
						<li class="nav-item ">
							<a role="tab" class="nav-link active setting-tabss" data-toggle="tab" href="#settingTab1">
								<span>General Settings</span>
							</a>
						</li>

						<li class="nav-item">
							<a role="tab" class="nav-link setting-tabss" data-toggle="tab" href="#settingTab2">
								<span>White Label</span>
							</a>
						</li>

						<li class="nav-item">
							<a role="tab" class="nav-link setting-tabss" data-toggle="tab" href="#settingTab3">
								<span>Integration</span>
							</a>
						</li>

						<li class="nav-item">
							<a role="tab" class="nav-link setting-tabss" data-toggle="tab" href="#settingTab4">
								<span>Dashboard Settings</span>
							</a>
						</li>	
				</ul>
				


					<div class="tab-content">
						<div id="settingTab1" class="SettingsSection tab-pane fade in show active ">
						
                           	<form class="col-xl-9 col-sm-12 form-horizontal" name="general_settings" method="post" id="general_settings" enctype="multipart/form-data">
                                            <input type="hidden" name="request_id" value="{{\Request::segment(2)}}">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Project Start Date </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="domain_register" id="domain_register" class="form-control settingsProjectStartDate" placeholder="Date" value="{{$dashboardtype->domain_register}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Target Location</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                    <select name="regional_db" id="regional_db" class="select form-control">
                                                       <?php 
														if(!empty($regional_db) && isset($regional_db)){
														foreach($regional_db as $db){
															?>
															<option value="{{$db->short_name}}" {{$db->short_name == $dashboardtype->regional_db  ? 'selected' : ''}}>{{$db->long_name}}</option>
														<?php
															}
														}
                                                       ?>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Website Name </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="domain_name" class="form-control" placeholder="Your site name here" value="{{$dashboardtype->domain_name}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Website URL</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="domain_url" class="form-control"  value="{{$dashboardtype->domain_url}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Client Name</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="clientName" class="form-control" placeholder="John Doe" value="{{$dashboardtype->clientName}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>Tags(Max 2)</label>
                                                    </div>
                                                    <div class="col-sm-8" >
                                                        <input type="text" id="input-tags" class="demo-default" placeholder="Add comma seperated tags" name="tags" value="{{@$dashboardtype->tags}}">
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class="text-left row">
                                                <div class="col-sm-8 col-sm-offset-4">
                                                    <button type="submit" class="mb-2 mr-2 btn btn-gradient-info" id="CampaigngeneralSettings"><i class="fa fa-paper-plane-o"></i> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                  
                        </div>

						<div id="settingTab2" class="SettingsSection tab-pane fade ">
						    <form class="col-xl-9 col-sm-12 form-horizontal" name="profile_details" method="post" id="profile_details" enctype="multipart/form-data">
                                             <input type="hidden" name="request_id" value="{{\Request::segment(2)}}">
                                            <h5 class="card-title">Agency Information</h5>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Agency Name
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="company_name" class="form-control" placeholder="Agency name here" value="{{@$profile_info->company_name}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Agency Owner Name
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="client_name" class="form-control" placeholder="Agency owner name here" value="{{@$profile_info->client_name}}" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Agency Phone
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="mobile" placeholder="(xxx) xxx-xxxx" value="{{@$profile_info->contact_no}}" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Agency Email
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" placeholder="agency@email.com" name="email" value="{{@$profile_info->email}}" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Agency Logo
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8 customFile">
                                                        <input id="whiteLableLogo" name="logo" type="file" class="file-loading">
                                                    </div>
                                                </div>
                                            </div>



                                           <!--   <h5 class="card-title">Account Manager Information</h5>
                                             <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Manager Name
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="manager_name" class="form-control" placeholder="Manager name here" value="{{@$profile_info->manager_name}}" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Manager Email
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="email" class="form-control" placeholder="manager@email.com" name="manager_email" value="{{@$profile_info->manager_email}}" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>
                                                            Manager Image
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-8 customFile">
                                                        <input id="managerImage" name="manager_image" type="file" class="file-loading">
                                                    </div>
                                                </div>
                                            </div> -->

                                            <div class="text-left row">
                                                <div class="col-sm-8 col-sm-offset-4">
                                                    <button type="submit" class="mb-2 mr-2 btn btn-gradient-info"><i
                                                            class="fa fa fa-paper-plane-o"></i> Submit</button>
                                                    
                                                </div>

                                            </div>

                                        </form>
                        </div>

                        <div id="settingTab3" class="SettingsSection tab-pane  fade">
                             <div class="integration-box">
                             <?php 
                             $types = array();
                            
                            if(isset($dashboardtype) && !empty($dashboardtype)){
                                $types = explode(',',$dashboardtype->dashboard_type);
                            }
                             ?>
                            
                             <?php 
                                if (in_array(1, $types)){

                             ?>
                                        <div class="box analytics <?php if(!empty($dashboardtype->google_account_id) && !empty($dashboardtype->google_analytics_id)){ echo 'active'; }?>">
                                        <!--img src="{{URL::asset('/public/vendor/images/ReportGoogleAnaLyticsGoals.png')}}" alt=""-->
                                            <h5>Connect Google Analytics</h5>
                                            <p>Select an existing account or connect a new Google Account</p>
                                            <button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#ConnectGoogleAnalyticsModal">Add Google Analytics Account</button>
                                            
                                        </div>
                                        
                                        <div class="box console <?php if(!empty($dashboardtype->google_console_id) && !empty($dashboardtype->console_account_id)){ echo 'active'; }?>">
                                            <!--img src="{{URL::asset('/public/vendor/images/google-logo-icon.png')}}" alt=""-->
                                            <h5>Connect Google Search Console</h5>
                                            <p>Select an existing account or connect a new Google Account</p>
                                            <button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#ConnectGoogleSearchConsoleModal">Add Google Search Console Account</button>
                                            
                                        </div>
                                        
                              <?php 
                                }
                               if (in_array(2, $types)){
                             ?>
                                        <div class="box <?php if(!empty($dashboardtype->google_ads_id)){ echo 'active'; }?>">
                                            <!--img src="{{URL::asset('/public/vendor/images/ReportGoogleAnaLyticsGoals.png')}}" alt=""-->
                                            <h5>Google Ads Account</h5>
                                            <p>Select an existing account or connect a new Google Ads Account </p>
                                            <button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#ConnectGoogleAdsModal">Add Google Ads Account</button>
                                        </div>
                               <?php }?>
                               
                               
                               
                                        
                            </div>
                           
                        </div>
						

						
                        <div id="settingTab4" class="SettingsSection tab-pane  fade">
                         
                            <form class="col-xl-9 col-sm-12 form-horizontal" name="dashboard_settings" method="post" id="dashboard_settings" >
                                            <input type="hidden" name="request_id" value="{{\Request::segment(2)}}">
                                            
                                            <?php 
                                            if(isset($dashboards) && !empty($dashboards)){
                                                foreach($user_dashboards  as $ud){
                                                    $types[] = $ud->dashboard_id;
                                                    }
                                               // $types = explode(',',$dashboardtype->dashboard_type);
                                                foreach($dashboards as $dashboard){

                                            ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label>{{$dashboard->name}}</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="custom-control custom-switch">
                                                          <input name="dashboard[{{$dashboard->id}}]" type="checkbox" class="custom-control-input btn btn-primary" id="customSwitches{{$dashboard->id}}" <?php if(in_array($dashboard->id, $types)){ echo "checked"; }?>>
                                                          <label class="custom-control-label" for="customSwitches{{$dashboard->id}}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php  } } ?>
                                       
                                            <div class="text-left row">
                                                <div class="col-sm-8 col-sm-offset-4">
                                                    <button type="submit" class="mb-2 mr-2 btn btn-gradient-info" id="CampaignDashboardSettings"><i class="fa fa-paper-plane-o"></i> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                 
                        </div>
                    </div>
				</div>
					  </div>
        </div>

    </div>
@endsection