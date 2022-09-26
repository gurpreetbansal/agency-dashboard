@extends('layouts.vendor_layout')
@section('content')

<div class="tabs-animation">
<div class="row">
    <div class="col-md-12 col-lg-6 col-xl-12">
        <div class="mb-3 card">
        	<div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <div class="left"> Archived Projects</div>
                     <div class="right filter col-md-1"><a href="{{url('/dashboard')}}"><button type="button" class="mb-2 mr-2 btn btn-gradient-info"><i class="fa fa-arrow-left"></i> BACK</button></a></div>
                </div>

            </div>
            <div class="card-body">
                <table class="table table-bordered data-table" id="archievedcampaigns">
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Searcher</th>
                            <th>Country</th>
                            <th>Created</th>
                            <th>Actions</th>
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