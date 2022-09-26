<div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
            <div class="card-body">
                <div id="smartwizard" class="sw-main sw-theme-default">
                    <ul class="forms-wizard nav nav-tabs step-anchor">
                        <li class="nav-item active">
                            <a href="#step-1" class="nav-link">
                                <em>1</em><span>Project Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#step-2" class="nav-link">
                                <em>2</em><span>Account Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#step-3" class="nav-link">
                                <em>3</em><span>Finish Setup</span>
                            </a>
                        </li>
                    </ul>
                    <div class="form-wizard-content sw-container tab-content" style="min-height: 353px;">
                        <div id="step-1" class="tab-pane step-content" style="display: block;">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="projectname">Project Name</label>
                                        <input name="domain_name" id="projectname" placeholder="Project Name" type="text" class="form-control" value="{{@$dashboardtype->domain_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="domain_url">Domain Url</label>
                                        <input name="domain_url" id="domain_url" placeholder="Domain Url" type="text" class="form-control disabled" value="{{@$dashboardtype->domain_url}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="exampleCity">Regional Database</label>
                                        <select class="form-control">
                                            @foreach($getRegions as $key=>$value)
                                            <option value="{{$value->short_name}}" {{$value->short_name == $dashboardtype->regional_db?'selected':''}}>{{$value->short_name . '('.$value->long_name.')'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-2" class="tab-pane step-content">
                            <div id="accordion" class="accordion-wrapper mb-3">
                                <div class="card">
                                    <div id="headingOne" class="card-header">
                                        <button type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="text-left m-0 p-0 btn btn-link btn-block">
                                            <span class="form-heading">Account Information<p>Enter your  user informations below</p></span>
                                        </button>
                                    </div>
                                    <div data-parent="#accordion" id="collapseOne" aria-labelledby="headingOne" class="collapse show">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="exampleEmail2">Email</label>
                                                        <input name="email" id="exampleEmail2" placeholder="with a placeholder" type="email" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="examplePassword">Password</label>
                                                        <input name="password" id="examplePassword" placeholder="password placeholder" type="password" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="position-relative form-group">
                                                <label for="exampleAddress">Address</label>
                                                <input name="address" id="exampleAddress" placeholder="1234 Main St" type="text" class="form-control">
                                            </div>
                                            <div class="position-relative form-group">
                                                <label for="exampleAddress2">Address 2</label>
                                                <input name="address2" id="exampleAddress2" placeholder="Apartment, studio, or floor" type="text" class="form-control">
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="exampleCity">City</label>
                                                        <input name="city" id="exampleCity" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <label for="exampleState">State</label>
                                                        <input name="state" id="exampleState" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group">
                                                        <label for="exampleZip">Zip</label>
                                                        <input name="zip" id="exampleZip" type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-3" class="tab-pane step-content">
                            <div class="no-results">
                                <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                    <span class="swal2-success-line-tip"></span>
                                    <span class="swal2-success-line-long"></span>
                                    <div class="swal2-success-ring"></div>
                                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                </div>
                                <div class="results-subtitle mt-4">Finished!</div>
                                <div class="results-title">You arrived at the last form wizard step!</div>
                                <div class="mt-3 mb-3"></div>
                                <div class="text-center">
                                    <button class="btn-shadow btn-wide btn btn-success btn-lg">Finish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="clearfix">
                    <button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Next</button>
                    <button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Previous</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>