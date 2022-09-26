            @extends('layouts.admin')
            @section('content')
            <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                <li class="nav-item">
                    <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="true">
                        <span>Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1" aria-selected="false">
                        <span>Settings</span>
                    </a>
                </li>
                 <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-2" aria-selected="false">
                        <span>Global Settings</span>
                    </a>
                </li>
                
            </ul>
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered data-table" id="SuperUser">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Client Name</th>
                                                    <th>Organisation Name</th>
                                                    <th>Package</th>
                                                    <th>Keywords</th>
                                                    <th>Action</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div id="announcements-section">
                                        @if(isset($announcements) && !empty($announcements))
                                        @foreach($announcements as $announcement)
                                        <div class="alert alert-{{$announcement->announcement_type}} alert-dismissible">
                                          {{$announcement->announcement}}
                                          <a href="javascript:;" class="closeAnnouncement" data-id="{{$announcement->id}}">&times;</a>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                   <form id="announcementForm">
                                    <div class="position-relative form-group">
                                        <label  class="">Enter Announcement</label>
                                        <input name="announcement"  id="announcement" type="text" class="form-control">
                                       <span class="error errorStyle"><p id="announcement_error"></p></span>
                                    </div>

                                    <div class="position-relative form-group">
                                        <label  class="">Select Type</label>
                                        <div class="custom-radio custom-control">
                                            <input type="radio" value="success" id="announcement_type" name="announcement_type">
                                            <label for="success">Success</label>
                                        </div>
                                        <div class="custom-radio custom-control">
                                            <input type="radio" value="info" id="announcement_type" name="announcement_type">
                                            <label for="info">Information</label>
                                        </div>
                                        <div class="custom-radio custom-control">
                                            <input type="radio"  value="warning" id="announcement_type" name="announcement_type">
                                            <label for="warning">Warning</label>
                                        </div>
                                        <div class="custom-radio custom-control">
                                            <input type="radio"  value="danger" id="announcement_type" name="announcement_type">
                                            <label for="danger">Danger</label>
                                        </div>
                                        <span class="error errorStyle"><p id="announcement_type_error"></p></span>
                                    </div>
                                    <a href="javascript:;"><button class="mt-1 btn btn-primary" id="saveAnnouncement" type="button">Submit</button></a>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                   <form id="globalsettingsForm">
                                    {{ csrf_field() }}
                                      <div class="custom-control custom-switch">
                                      
                                          <input type="checkbox" class="custom-control-input" id="maintenanceMode" name="example" {{$globalSettings->status==1?'checked':''}}>
                                          <label class="custom-control-label" for="maintenanceMode">Maintenance Mode</label>
                                      </br>
                                          <span style="font-size: 10px;">Enable switch to put the site in maintenance mode.</span>
                                        </div>                                 
                                    <a href="javascript:;"><button class="mt-1 btn btn-primary" id="globalsettings" type="button">Submit</button></a>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

        </div>

        @endsection