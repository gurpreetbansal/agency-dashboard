@extends('layouts.vendor_layout')
@section('content')


<div class="tabs-animation">

     <?php if(isset($dfs_user_data) && !empty($dfs_user_data)){
     if($dfs_user_data->balance > 50){ ?>
    <div class="alert alert-success">
       <span>Balance left for Data For seo: <strong>{{'$'.$dfs_user_data->balance}}</strong></span>
    </div>
    <?php }elseif($dfs_user_data->balance <=50){ ?>
        <div class="alert alert-danger">
        <span>Data For Seo balance less than $50, <strong>Please Recharge</strong> </span>
        <span>Current Balance :{{'$'.$dfs_user_data->balance}}</span>
    </div>
    <?php } } ?>
    @if($role == '2')
     @if(isset($message) && !empty($message))
    <div class="alert alert-{{$message->banner}}">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{$message->message}}
    </div>
    @endif
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-night-fade">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Keywords</div>
                        <div class="widget-subheading"></div>
                    </div>
                    <div class="widget-content-right">
                        <?php 
                        if(isset($user_package->keywords)){
                            $keywords = $user_package->keywords;
                        }else{
                            $keywords = 0;
                        }
                        ?>
                        <div class="widget-numbers text-white"><span>{{$keywordsCount}}/{{$keywords}}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Projects</div>
                        <div class="widget-subheading"></div>
                    </div>
                    <div class="widget-content-right">

                     <?php 
                     if(isset($user_package->projects)){
                        $projects = $user_package->projects;
                    }else{
                        $projects = 0;
                    }
                    ?>
                    <div class="widget-numbers text-white"><span>{{@$project_count}}/{{ $projects}}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-happy-green">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Subscription</div>
                    <div class="widget-subheading"></div>
                </div>
                <div class="widget-content-right">
                  <?php 
                  if(isset($user_package->package->name)){
                    $package_name = $user_package->package->name;
                }else{
                    $package_name = 0;
                }
                ?>
                <div class="widget-numbers text-white"><span>{{$package_name}}</span></div>
            </div>
        </div>
    </div>
</div>

</div>

@endif

<div class="row">

    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <div class="left"> Active Projects</div>

                    @if($role == '2')
                    <div class="right filter col-md-1">
                        <div class="btn-group dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
                                <i class="pe-7s-menu btn-icon-wrapper"></i>
                            </button>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
                                <a href="{{url('/archived-projects')}}">
                                    <button type="button" tabindex="0" class="dropdown-item archievedProjects">
                                        <i class="fa fa-file-archive-o"></i><span>&nbsp;Archieved Projects</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="right filter col-md-3">
                        <select class="form-control" id="ManagerList">
                            <option value="">Manager Search</option>
                            @if(isset($get_managers) && !empty($get_managers))
                            @foreach($get_managers as $manager)
                            <option value="{{$manager->name}}">{{$manager->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="right filter col-md-5">

                        <select class="js-example-basic-multiple form-control" multiple="multiple"></select>
                        <input type="hidden" class="selectedCam">
                    </div>

                    @endif
                </div>

            </div>
            <div class="card-body">

                <table class="table table-bordered data-table" id="campaigns">
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Searcher</th>
                            <th>Country</th>
                            <th>Keywords</th>
                            <th>Backlinks</th>
                            @if($role == '2')
                            <th>Actions</th>
                            @endif
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
@endsection