<?php

require_once("HTTP/Request2.php");

class NovaClient{
    var $token = "";
    var $baseUrl = "http://192.168.100.80";
    var $flovar_id = 'ef53c15b-104c-4e40-9e5c-ba2cd15556a0';
    var $network = 'd94ca3ea-3946-4b12-9e4c-8ceeafac60c3';
    var $security_groups_name = "default";
    public function Login($user,$pass)
    {
		$request = new Http_Request2($this->baseUrl.":5000/v2.0/tokens", HTTP_Request2::METHOD_POST);
		$request->setHeader("Content-Type","application/json");
		$request->setHeader("Accept","application/json");
		$request->setBody('{"auth": {"tenantName": "admin", "passwordCredentials": {"username": "'.$user.'", "password": "'.$pass.'"}}}');
		$response = $request->send();
		try{
			if(200 == $response->getStatus())
			{
				$jsonData = $response->getBody();
				$qData = json_decode($jsonData);
				$this->token = $qData->access->token->id;
				return true;
			}
		}
		catch (HTTP_Request2_Exception $e) {
		}
		return false;
    }
    public function GetImageList()
    {
    	$request = new Http_Request2($this->baseUrl.":8774/v2/c316ab8c05214fff84e0b8b7e18c8d00/images/detail", HTTP_Request2::METHOD_GET);
    	$request->setHeader("X-Auth-Token",$this->token);
    	$request->setHeader("Accept","application/json");
    	$response = $request->send();
    	try{
    		if(200 == $response->getStatus())
    		{
    			$jsonData = $response->getBody();
    			$qData = json_decode($jsonData);
    			return $qData;
    		}else
    			return null;
    	}
    	catch (HTTP_Request2_Exception $e) {
    	}
    }
    public function GetInstantList()
    {
    	$request = new Http_Request2($this->baseUrl.":8774/v2/c316ab8c05214fff84e0b8b7e18c8d00/servers/detail", HTTP_Request2::METHOD_GET);
    	$request->setHeader("X-Auth-Token",$this->token);
    	$request->setHeader("Accept","application/json");
    	$response = $request->send();
    	try{
    		if(200 == $response->getStatus())
    		{
    			$jsonData = $response->getBody();
    			$qData = json_decode($jsonData);
    			return $qData;
    		}else
    			return null;
    	}
    	catch (HTTP_Request2_Exception $e) {
    	}
    }
    public function CreatInstant($machine_id,$instant_name)
    {
    	$request = new Http_Request2($this->baseUrl.":8774/v2/c316ab8c05214fff84e0b8b7e18c8d00/servers", HTTP_Request2::METHOD_POST);
    	$request->setHeader("X-Auth-Token",$this->token);
    	$request->setHeader("Content-Type","application/json");
    	$request->setHeader("Accept","application/json");
    	$server = array("server"=>array ("name"=>$instant_name,"imageRef"=>$machine_id,
    			"flavorRef"=>"ef53c15b-104c-4e40-9e5c-ba2cd15556a0","max_count"=>1,"min_count"=>1,
    			"networks"=>array(array("uuid"=>"d94ca3ea-3946-4b12-9e4c-8ceeafac60c3")), 
    			"security_groups"=>array(array("name"=>"default"),array("name"=>"default"))));
    	$request->setBody(json_encode($server));
    	$response = $request->send();
    	try{
    		if(202 == $response->getStatus())
    		{
    			$jsonData = $response->getBody();
    			$qData = json_decode($jsonData);
    			return $qData;
    		}else
    			return null;
    	}
    	catch (HTTP_Request2_Exception $e) {
    	}
    }
    public function UpDateInstant($instant_id, $instant_name)
    {
    	$request = new Http_Request2($this->baseUrl.":8774/v2/c316ab8c05214fff84e0b8b7e18c8d00/servers/".$instant_id, HTTP_Request2::METHOD_PUT);
    	$request->setHeader("X-Auth-Token",$this->token);
    	$request->setHeader("Content-Type","application/json");
    	$request->setHeader("Accept","application/json");
    	$server = array("server"=>array("name"=>$instant_name));
    	$request->setBody(json_encode($server));
    	$response = $request->send();
    	try{
    		if(200 == $response->getStatus())
    		{
    			$jsonData = $response->getBody();
    			$qData = json_decode($jsonData);
    			return $qData;
    		}else
    			return null;
    	}
    	catch (HTTP_Request2_Exception $e) {
    	}
    }
    public function StartInstant($instant_id)
    {
    	$request = new Http_Request2($this->baseUrl.":8774/v2/c316ab8c05214fff84e0b8b7e18c8d00/servers/".$instant_id."/action", HTTP_Request2::METHOD_POST);
    	$request->setHeader("X-Auth-Token",$this->token);
    	$request->setHeader("Content-Type","application/json");
    	$request->setHeader("Accept","application/json");
    	$resume = array("os-start"=>"null");
    	$request->setBody(json_encode($resume));
    	$response = $request->send();
    	try{
    		if(202 == $response->getStatus())
    		{
    			$jsonData = $response->getBody();
    			$qData = json_decode($jsonData);
    			return $qData;
    		}else
    			return null;
    	}
    	catch (HTTP_Request2_Exception $e) {
    	}
    }
    public function StopInstant($instant_id)
    {
    	$request = new Http_Request2($this->baseUrl.":8774/v2/c316ab8c05214fff84e0b8b7e18c8d00/servers/".$instant_id."/action", HTTP_Request2::METHOD_POST);
    	$request->setHeader("X-Auth-Token",$this->token);
    	$request->setHeader("Content-Type","application/json");
    	$request->setHeader("Accept","application/json");
    	$resume = array("os-stop"=>"null");
    	$request->setBody(json_encode($resume));
    	$response = $request->send();
    	try{
    		if(202 == $response->getStatus())
    		{
    			$jsonData = $response->getBody();
    			$qData = json_decode($jsonData);
    			return $qData;
    		}else
    			return null;
    	}
    	catch (HTTP_Request2_Exception $e) {
    	}
    }
}

?>