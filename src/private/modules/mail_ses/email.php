<?php

/*
$MODULE_DIR = '../';
$HOST = "23.23.26.42";
require "/usr/lib/php/7.0/Smarty/Smarty.class.php";

require 'aws/aws-autoloader.php';
use Aws\Ses\SesClient;

echo "starting script\n";
echo "test: $MODULE_DIR\n";
$template = $MODULE_DIR . 'mail_ses/password_reset.tpl';
$result = sendVerificationEmail("","","Reset your password", $template);
if($result == false){
	echo "false\n";
}
else{
	echo "true\n";
}
echo "end of script\n";
*/

/**
 * Sends formatted Verification email to user
 * @param String - recipient
 * @param String - verification key
 * @param String - subject of email
 * @param String - file path to tpl file, to send as email
 * @return True success
 */
function sendVerificationEmail($recipient,$key,$subject,$template){

	global $MODULE_DIR;
	global $HOST;

	//$subject = "Verify your email";

	$smarty_email = new Smarty();
	$smarty_email->assign("email",$recipient);
	$smarty_email->assign("key",$key);
	$smarty_email->assign("HOST",$HOST);

	//$body = $smarty_email->fetch( $MODULE_DIR . 'mail_ses/verification_email.tpl');
	$body = $smarty_email->fetch( $template );

	return sendEmail($recipient, $subject, $body);
}

/**
 * Sends email to recipient with subject and body
 * @param recipient - String
 * @param subject - String
 * @param body - String
 * @return boolean - False if email failed to send
 */
function sendEmail($recipient,$subject,$body){

	if( getAccess() == false){
		return false;
	}

	$sender = "noreplylivestocktracker@gmail.com";
	$region = "us-east-1";

	$client = SesClient::factory(array(
		'version' => 'latest',
		'region' => $region
	));

	$request = array();
	$request['Source'] = $sender;
	$request['Destination']['ToAddresses'] = array($recipient);
	$request['Message']['Subject']['Data'] = $subject;
	$request['Message']['Body']['Html']['Data'] = $body;

	try{
		$result = $client->sendEmail($request);
		$messageId = $result->get('MessageId');
		return true;
	} catch (Exception $e){
		$message = $e->getMessage();
		return false;
	}

}


/**
 * Reads the aws id and key and sets them as environment variables to get access
 * to aws SES
 * @param boolean - False if secret.json file did not exist
 */
function getAccess(){

	global $MODULE_DIR;
	$file = $MODULE_DIR . "mail_ses/secret.json";

	if( file_exists($file) ){

		$contents = json_decode( file_get_contents($file), true );
		$id = $contents['AWS_ACCESS_KEY_ID'];
		$key = $contents['AWS_SECRET_ACCESS_KEY'];

		putenv("AWS_ACCESS_KEY_ID=$id");
		putenv("AWS_SECRET_ACCESS_KEY=$key");

		return true;
	}
	else{
		return false;
	}
}
?>
