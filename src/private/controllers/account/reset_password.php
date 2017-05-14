<?php

if( isset($_GET['email']) && isset($_GET['key']) ){

	$email = $_GET['email'];
	$key = $_GET['key'];

	require_once $MODELS.'email_keys/EmailKey.php';

	$account_id = EmailKey::verify($email, $key, $KEY_TYPE['password_reset'] );

	if( $account_id == false){
		redirect_url('/account/reset_email?email=' . $email);
	}
	else{
		//everything is good
	}

	if( $_SERVER['REQUEST_METHOD'] === 'POST' ){

		if( isset( $_POST['Reset'] ) && isset( $_POST['password'] ) && isset( $_POST['password2'] ) ){

			if( strcmp( $_POST['password'], $_POST['password2'] ) == 0 ){

				require_once $MODELS.'account/Account.php';

				$result = Account::newPassword($account_id, $_POST['password'] );
			}
			else{
				echo "passwords do not match";
			}
		}
		else{
			echo "Enter in new password";
		}
	}
}
else{
	redirect_url('/home');
}

$smarty->display("account/reset_pass.tpl");
?>
