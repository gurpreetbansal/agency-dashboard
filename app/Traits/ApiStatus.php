<?php

namespace App\Traits;

trait ApiStatus {

	public function success($message='',$data=null,$additional=null,$code =200,$status =true){

		if($additional !== null){
			return response()->json([
	            'code' => $code,
	            'success' => $status,
	            'message' => $message,
	            'data'	=>	$data,
	            'token'=>$additional
	        ]); 

		}else{
			return response()->json([
	            'code' => $code,
	            'success' => $status,
	            'message' => $message,
	            'data'	=>	$data
	        ]); 
		}

	}

	public function fail($message='',$code =200,$status =false){
			return response()->json([
	            'code' => $code,
	            'success' => $status,
	            'message' => $message,
	        ]); 
	}
}