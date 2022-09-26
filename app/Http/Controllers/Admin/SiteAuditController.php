<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AuditErrorList;

class SiteAuditController extends Controller {

    public function index(){
        return view('Admin.site_audit.index');
    }

    public function create(){
        return view('Admin.site_audit.add');
    }


    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'error_key' => 'required',
            'error_label' => 'required',
            'short_description' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } else {
            $create = AuditErrorList::create([
                'category' => $request->category,
                'error_key' => $request->error_key,
                'error_label' => $request->error_label,
                'short_description'=>$request->short_description,
                'description'=>$request->description
            ]);

            if ($create) {
                return redirect('admin/site-audit')->with('success', 'Details added successfully.');
            } else {
                return back()->with('error', 'Error adding details.');
            }
        }
    }

    public function ajax_fetch_audit_list_data(Request $request){
        if($request->ajax())
        {
            $limit = $request['limit'];
            $query = $request['query'];
            $data = $this->audit_list_data($limit,$query);
            return view('Admin.site_audit.table', compact('data'))->render();
        }
    }

    public function ajax_fetch_audit_list_pagination(Request $request){
        if($request->ajax())
        {
            $limit = $request['limit'];
            $query = $request['query'];
            $data = $this->audit_list_data($limit,$query);
            return view('Admin.site_audit.pagination', compact('data'))->render();
        }
    }

    private  function audit_list_data ($limit,$query){  
        $field = ['error_key','error_label'];
        $users = AuditErrorList::select('id','category','error_key','error_label')
        ->where(function ($q) use($query, $field) {
            for ($i = 0; $i < count($field); $i++){
                $q->orwhere($field[$i], 'LIKE',  '%' . $query .'%');
            }      
        })
        ->paginate($limit); 

        return $users;
    }

    public function edit ($id){
        $data = AuditErrorList::findOrFail($id); 
        return view('Admin.site_audit.update',compact('data'));
    }

     public function update(Request $request, $id)
    {
        $requestData = $request->all();        
        $post = AuditErrorList::findOrFail($id);
        $post->update($requestData);
        return redirect('admin/site-audit')->with('success', 'Details updated!');
    }

    public function destroy($id)
    {
        AuditErrorList::destroy($id);

        return redirect('admin/site-audit')->with('success', 'Details deleted!');
    }

}