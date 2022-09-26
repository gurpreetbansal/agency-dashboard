<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProjectCompareGraph extends Model {

    protected $table = 'project_compare_graph';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['user_id', 'request_id', 'compare_status'];
	
	public static function getCompareChart($request_id){
		$res = ProjectCompareGraph::where('request_id',$request_id)->first();
		return $res;
	}

}
