<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Package;
use DataTables;


class ClientsController extends Controller {

    public function index(Request $request) {
        if ($request->ajax()) {
            $data = User::where('role_id', 2)->with('UserPackage')->latest()->get();
            if (!empty($data)) {
                foreach ($data as $key => $user) {
                    if ($user->UserPackage) {
                        $findPackage = Package::where('id', $user->UserPackage->package_id)->select('name', 'amount')->first();
                        $data[$key]->package = $findPackage->name . ' ($' . $findPackage->amount . ')';
                    } else {
                        $data[$key]->package = '';
                    }
                }
            }
            return Datatables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', function($row) {

                                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Manage" class="edit btn btn-primary btn-sm manage_client">Manage</a>';
                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        $users = User::where('role_id', 2)->with('UserPackage')->latest()->paginate(10);

        if (!empty($users)) {
            foreach ($users as $key => $user) {
                if ($user->UserPackage) {
                    $findPackage = Package::where('id', $user->UserPackage->package_id)->select('name', 'amount')->first();
                    $users[$key]->package = $findPackage->name . ' ($' . $findPackage->amount . ')';
                } else {
                    $users[$key]->package = '';
                }
            }
        }
        return view('admin.clients.index', ['users' => $users]);
    }

    public function show($id = null) {
        $user = User::with('Subscription')->with('UserAddress')->with('UserPackage')->with('UserPackage.package')->with('UserAddress.Country')->where('id', $id)->first();
        return view('admin.clients.show', ['user' => $user]);
    }

}
