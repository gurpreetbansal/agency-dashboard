@extends('layouts.admin')
@section('content')

<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Client Insight </div>
                <div class="card-body">

                    <a href="{{ url('/admin/clients') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>


                    <br/>
                    <br/>

                    <div class="profile-container">
                        <div class="row row-space-20">
                            <div class="col-md-6">
                                <ul class="profile-info-list">
                                    <li class="title"><h4>Personal Information</h4></li>
                                    <li>
                                        <div class="field">{{$user->name}}</div>
                                        <div class="value">{{$user->email}}</div>
                                        <div class="value">{{$user->phone}}</div>
                                    </li>


                                    <li class="title"><h6>Address</h6></li>
                                    <li>
                                        <div class="field">{{@$user->UserAddress->address_line_1}}</div>
                                        <div class="value">{{@$user->UserAddress->address_line_2}}</div>
                                        <div class="value">{{@$user->UserAddress->city.' , '.@$user->UserAddress->Country->countries_name.' , '.@$user->UserAddress->zip}}</div>
                                    </li>

                                </ul>
                            </div>

                            <div class="col-md-3 hidden-xs hidden-sm">
                                <ul class="profile-info-list">
                                    <li class="title"><h4>Subscription</h4></li>
                                    <li>
                                        <div class="field">{{@$user->UserPackage->package->name}}</div>
                                        <div class="value">{{'$'.@$user->UserPackage->package->amount}}</div>
                                        <div  class="value"><?php
                                            if (strtotime(@$user->Subscription->trial_ends_at) >= time() + 300) {
                                                echo "Trial ends at ". $user->Subscription->trial_ends_at;
                                            } else {
                                                echo "Active";
                                            }
                                            ?></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3 hidden-xs hidden-sm">
                                <div class="page-title-actions">
                                    <div class="d-inline-block dropdown">
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-success">

                                            Active
                                        </button>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a href="javascript:void(0);" class="nav-link">
                                                        <i class="nav-link-icon fa fa-pause"></i>
                                                        <span>
                                                            Suspend
                                                        </span>

                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="javascript:void(0);" class="nav-link">
                                                        <i class="nav-link-icon fa fa-user-times"></i>
                                                        <span>
                                                            Delete
                                                        </span>

                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>


                        <div class="row row-space-20" style="margin-top:30px">
                            <div class="col-md-6 hidden-xs hidden-sm">
                                <ul class="profile-info-list">
                                    <li class="title"><h4>Campaigns</h4></li>
                                    <li>
                                        <div class="field company_name">Imarkinfotech Pvt Ltd</div>
                                        <div class="value user">Ishan Gupta</div>
                                        <div class="value website">https://www.imarkinfotech.com</div>
                                    </li>
                                    <li>
                                        <div class="field company_name">Imarkinfotech Pvt Ltd</div>
                                        <div class="value user">Ishan Gupta</div>
                                        <div class="value website">https://www.imarkinfotech.com</div>
                                    </li>

                                    <li>
                                        <div class="field company_name">Imarkinfotech Pvt Ltd</div>
                                        <div class="value user">Ishan Gupta</div>
                                        <div class="value website">https://www.imarkinfotech.com</div>
                                    </li>

                                </ul>
                            </div>

                            <div class="col-md-6 hidden-xs hidden-sm invoices_section">
                                <ul class="profile-info-list">
                                    <li class="title"><h4>Invoices</h4></li>
                                    <li>
                                        <div class="field">August 2020 <i class="metismenu-icon fa fa-download"></i></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
