<?php

namespace App\Traits;

require config("app.FILE_PATH").'RestClient.php';

trait ClientAuth {
	
	
	public function DFSAuth(){
		$base_uri = config('app.DFS_URI');
		$user = config('app.DFS_USER');
		$pass = config('app.DFS_PASS');
		$client = new \RestClient($base_uri, null, $user, $pass);
		return $client;
	}
	
	public function locations($client){
		try {
			$result = $client->get('/v3/keywords_data/google/locations');
			return $result;
		} catch (RestClientException $e) {
			
			return json_decode($e->getMessage(), true);
		}
	}


	public static function DFSAuthConfig(){
		$base_uri = config('app.DFS_URI');
		$user = config('app.DFS_USER');
		$pass = config('app.DFS_PASS');
		$client = new \RestClient($base_uri, null, $user, $pass);
		return $client;
	}
	
	
}