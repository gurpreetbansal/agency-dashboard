@extends('layouts.admin')
@section('content')

 <?php if(isset($dfs_balance) && !empty($dfs_balance)){
     if($dfs_balance->balance > 50){ ?>
    <div class="alert alert-success">
       <span>Balance left for Data For seo: <strong>{{'$'.$dfs_balance->balance}}</strong></span>
    </div>
    <?php }elseif($dfs_balance->balance <=50){ ?>
        <div class="alert alert-danger">
        <span>Data For Seo balance less than $50, <strong>Please Recharge</strong> </span>
        <span>Current Balance :{{'$'.$dfs_balance->balance}}</span>
    </div>
    <?php } } ?>
<div class="tabs-animation">
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-night-fade">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Number of Projects</div>
                        <div class="widget-subheading">(In last 30 days)</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{$thirty_Day_projects}}/{{$total_projects}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Number of Keywords</div>
                        <div class="widget-subheading">(In last 30 days)</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{$thirty_Day_keywords}}/{{$total_keywords}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-happy-green">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Number of Clients</div>
                        <div class="widget-subheading">(In last 30 days)</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{$thirty_day_user}}/{{$user_count}}</span></div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="fa fa-users"></i> &nbsp;
                        Ten Recent Signups 
                    </div>

                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tabs-eg-77">
                            <div class="scroll-area-sm">
                                <div class="scrollbar-container">
                                    <ul
                                        class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">

                                        <?php

                                        if (isset($recent_signups) && !empty($recent_signups)) {
                                            foreach ($recent_signups as $signup) {
                                                ?>
                                                <li class="list-group-item">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <?php
                                                                if(!empty($signup->profile_image)){
                                                                ?>
                                                                <img style="width: 42px;height: 42px;" class="rounded-circle" src="{{ asset('public/storage/'.$signup->profile_image) }}" alt="">
                                                                <?php }else{?>
                                                                <img width="42" class="rounded-circle" src="{{url('public/assets/images/no-user-image.png')}}" alt="">
                                                                <?php }?>
                                                            </div>
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">{{$signup->name}}</div>
                                                                <div class="widget-subheading">{{$signup->company_name}}</div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="font-size-xlg text-muted">
                                                                    <small class="opacity-5 pr-1"><?php 
                                                                    if(isset($signup->Package->name)){ echo $signup->Package->name;}else{ echo '-';}?></small>
                                                                    <span><?php if(isset($signup->Package->amount)){ echo '$'.$signup->Package->amount; }else{ echo'-'; }?></span>
                                                                    <small class="text-danger pl-2">
                                                                        <!--<i class="fa fa-angle-down"></i>-->
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-6">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <i class="fa fa-lock"></i> &nbsp;
                        Last Login at
                    </div>

                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tabs-eg-77">
                            <div class="scroll-area-sm">
                                <div class="scrollbar-container">
                                    <ul
                                        class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">

                                        <?php

                                        if (isset($logins) && !empty($logins)) {
                                            foreach ($logins as $login) {
                                                ?>
                                                <li class="list-group-item">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">{{$login->name}}</div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="font-size-xlg text-muted">
                                                                    <small class="opacity-5 pr-1"><?php 
                                                                    if(!empty($login->last_login)){
                                                                        echo date("M d'Y H:i:s",strtotime($login->last_login));
                                                                    }else{
                                                                        echo "-";
                                                                    }
                                                                    ?></small>
                                                             
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
       
    </div>
 
</div>


@endsection