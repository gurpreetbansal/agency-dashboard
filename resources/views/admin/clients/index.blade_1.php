@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Clients</div>
                <div class="card-body">
                    <form method="GET" action="{{ url('/admin/clients') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                            <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>

                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name</th><th>Email</th><th>Company Name</th><th>Package</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($users) && !empty($users))
                                @foreach($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->company_name }}</td>
                                    <td>{{ $item->Package->name .' ($'.$item->Package->amount.')' }}</td>
                                    <td>
                                        <!--<a href="{{ url('/admin/clients/' . $item->id) }}" title="View Client">-->
                                            <button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button>
                                        <!--</a>-->
                                        <!--<a href="{{ url('/admin/clients/' . $item->id . '/edit') }}" title="Edit Client">-->
                                            <button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>
                                        <!--</a>-->

                                        <form method="POST" action="{{ url('/admin/clients' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Client" onclick="return confirm( & quot; Confirm delete? & quot; )"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $users->appends(['search' => Request::get('search')])->render() !!} </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
