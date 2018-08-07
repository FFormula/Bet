<?php

namespace FFormula\BF;

class Bet
{
	private $url = "https://api.betfair.com/exchange/betting/json-rpc/v1";
	private $app_key;
	private $session;

	public function __construct($app_key, $session)
	{
		$this->app_key = $app_key;
		$this->session = $session;
	}

	public function getAllEventTypes($eventTypeIds = 2)
	{
		$response = $this -> callService('listEventTypes', 
'{
	"filter": 
	{
		"eventTypeIds":["' . $eventTypeIds . '"]
	}
}');
		$result = $this -> parseResponse($response);
		return $result;
	}

	protected function callService ($method, $params)
	{
	    $cu = curl_init();
	    curl_setopt($cu, CURLOPT_URL, $this -> url); 
	    curl_setopt($cu, CURLOPT_POST, 1);
	    curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($cu, CURLOPT_HTTPHEADER, array(
		'X-Application: ' . $this->app_key,
		'X-Authentication: ' . $this->session,
		'Accept: application/json',
		'Content-Type: application/json'
	    ));

	    $post = 
'[{ 
	"jsonrpc": "2.0", 
	"method": "SportsAPING/v1.0/' . $method. '", 
	"params": ' . $params . ', 
	"id": 1
}]';

	    curl_setopt($cu, CURLOPT_POSTFIELDS, $post);
	    $response = curl_exec($cu);
	    curl_close($cu);

	    echo('Post: ' . $post . "\n");
	    echo('Response: ' . $response . "\n");

	    return $response;
	}

	protected function parseResponse ($response)
	{
	    try {
	        $responseData = json_decode ($response);
		if (isset ($responseData[0]->error)) 
		    return $responseData[0]->error;
		return $responseData[0]->result;
	    } catch (Exception $e) {
		return $e->getMessage();
	    }
	}
}

