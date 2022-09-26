@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <!--<div class="card-header">Clients</div>-->
                <div class="card-body">
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table clients">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company Name</th>
                                    <th>Package</th>
                                    <th width="90px">Action</th>
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


@endsection
