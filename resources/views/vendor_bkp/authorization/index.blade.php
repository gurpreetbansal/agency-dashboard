@extends('layouts.vendor_layout')
@section('content')
<div class="tabs-animation">
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title">
                        <div class="left"> Active Projects</div>
                    </div>

                </div>
                <div class="card-body">
                    <table class="table table-bordered data-table" id="auth_campaigns">
                        <thead>
                            <tr>
                                <th>Domain</th>
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