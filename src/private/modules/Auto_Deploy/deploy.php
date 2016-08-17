<?php

$response = array( "deployed" => false);

$file = $MODULE_DIR . "Auto_Deploy/secret.json";

//check if secret key file exists
if(!file_exists($file)){
	$response['deployed'] = false;
	$response['error'] = "no key defined";
	json_response($response);
}

//get key
$secret_key = json_decode( file_get_contents( $file ), true );

//get payload data
$payload = json_decode( file_get_contents('php://input'), true );

//verify payload exists
if( !isset($payload['repository']['full_name']) || !isset($payload['sender']['login'])
	|| !isset($_SERVER['HTTP_X_HUB_SIGNATURE'])){

	$response['error'] = "unknown message";
	json_response($response);
}

//check if sender as the correct key
$signature = 'sha1=' . hash_hmac("sha1", file_get_contents('php://input'), $secret_key['key']);

if( $_SERVER['HTTP_X_HUB_SIGNATURE'] !== $signature ){
	$response['error'] = "security error";
	$response['security'] = "unauthorized sender";
	json_response($response);
}

//check if sender provided correct repo and login
if( $payload['repository']['full_name'] !== $secret_key['repo_name']
 || !in_array( $payload['sender']['login'], $secret_key['authorized_logins'] ) ){

	 $response['error'] = "invalid access";
	 json_response($response);
 }
 else{
	 $response['access'] = "granted";
 }

//update code base
echo shell_exec("git reset --hard;git checkout master; git pull origin master");

//update database
echo shell_exec("sh " . $SCRIPTS . "db_setup.sh");
$response['deployed'] = true;
echo date("Y/m/d h:i:sa");

json_response($response);
?>
