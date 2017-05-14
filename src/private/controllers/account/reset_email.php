<?php

$username_or_email = "";
if( isset($_GET['user'] ) ){
	$username_or_email = $_GET['user'];
}

$result = "";

if( $_SERVER['REQUEST_METHOD'] === 'POST' ){

	if( isset($_POST['send_email']) && isset($_POST['username']) ){

		require_once $MODELS.'account/Account.php';
		require_once $MODELS.'email_keys/EmailKey.php';

		//check if account exists and is activated
		$active = Account::isActivated( $_POST['username'] );


		if( $active == "email does not exist"){
			$result = "Account does not exist";
		}
		else if( $active == false){
			$result = "Account needs to be verified first";
		}
		else if( $active > 0){

			//create new key for account
			$account = Account::getAccountFromId($active);
			$key = EmailKey::createNewEmailKey( $account, $KEY_TYPE['password_reset'] );

			//TODO:send reset email


			$result = "email sent";
		}

	}
}

$smarty->assign("result",$result);
$smarty->assign("sendmail_active", active_module("sendmail") );
$smarty->assign("username_or_email", $username_or_email);
$smarty->display('account/reset_email.tpl');
?>