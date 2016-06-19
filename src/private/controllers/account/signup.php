<?php
	require $MODELS.$DATABASE;
	require $MODELS.'account/Account.php';

	if(  $_SERVER['REQUEST_METHOD'] === 'POST' ){
		if( isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['email'] ) && isset( $_POST['password2'] ) ) {
			if( strcmp( $_POST['password'] , $_POST['password2'] == 0) ){
				$account = Account::createAccount( $_POST['username'], $_POST['email'], $_POST['password'] );
				if( $account === false ){
					echo "an error occurred";
				}
				else {
					echo "signup successful!!!";
				}

				echo "some stuff";
			}
			else{
				echo "passwords don't match";
			}
		
		}
	}

	db_close();

	$smarty->display("account/signup.tpl");

?>
