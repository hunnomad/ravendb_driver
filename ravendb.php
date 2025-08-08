<?php

	class RavenDB {
		var $server;
		var $database;
		var $pem;

		function __construct($server, $database, $pem = NULL) {		
			$this->server = $server;
			$this->database = $database;
			$this->pem = $pem;
		}
		
		function put($id, $doc) {
		    $url = $this->_url("/docs?id=" . $id);
		    $body = json_encode($doc);
		    return $this->_exec("PUT", $url, 201, $body);
	    }

		function get($id) {
		    $url = $this->_url("/docs?id=" . $id);
		    return $this->_exec("GET", $url, 200, NULL)->Results[0];
	    }
	    
	    function query($query, $args = NULL) {
	        $r = $this->raw_query($query, $args);
	   
	        return $r->Results;
	    }
	    
	    function raw_query($query, $args = NULL) {
		    $url = $this->_url("/queries");
		    $body = json_encode(array("Query" => $query, "QueryParameters" => $args));
		    return $this->_exec("POST", $url, 200, $body);
	    }
	    
	    function del($id) {
		    $url = $this->_url("/docs?id=" . $id);
		    $this->_exec("DELETE", $url, 204, NULL);
	    }
	    
	    private function _exec($method, $url, $expectedStatusCode, $body) {
	        $curl = curl_init($url);
			try{
    			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '2');
    			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '1');
    			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    			curl_setopt($curl, CURLOPT_SSLCERT, $this->pem);
    			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    			if($body != NULL){
    			    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    			}
				$response = curl_exec($curl);
				if (!curl_errno($curl)) {
				  switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
				    case $expectedStatusCode:  
				    	return json_decode($response);
				    case 404:
				    	return NULL;
				    default:
				        echo $response;
				      throw new Exception( $url . " GOT "  . $http_code . " - " . $response);
				  }
				}
			}
			finally{
				curl_close($curl);
			}
	    }

		private function _url($path) {
			return $this->server . "/databases/" . $this->database . $path;
		}

	}

?>