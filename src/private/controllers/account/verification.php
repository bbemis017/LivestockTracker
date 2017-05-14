<?php

if( isset( $_GET['key'] ) && isset( $_GET['email'] ) ){

	require_once $MODELS.'email_keys/EmailKey.php';
	require_once $MODELS.'account/Account.php';

	$key = $_GET['key'];
	$email = $_GET['email'];

	$account_id = EmailKey::verify( $email, $key, $KEY_TYPE['verification'] );

	if( $account_id == false ){
		//request was not valid
		//TODO: resend email
		echo "invalid request\n";
		echo "resend email?\n";
	}
	else{

		$result = Account::activate($account_id);
		if( $result == true){
			echo "Account Activated\n";
		}
		else{
			echo "Account failed to Activate\n";
		}
	}
}
else{
	//request did not provide a key and email
	//TODO: redirect to 404 page
	echo "404";
	exit();
}

?>
