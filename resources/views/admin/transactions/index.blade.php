@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <!--<div class="card-header">Transactions</div>-->
                <div class="card-body">
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table transactions">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Stripe Subscription Id</th>
                                    <th>Package Purchased</th>
                                    <th>Refunded Amount</th>
                                    <th>Action</th>
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
