<?php
    require $MODELS.'db_connect.php';
    require $MODELS.'account/Account.php';
	echo "</br>";
	echo "here is the login";

	//if request is post than a user is trying to login
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){

        
		if( isset( $_POST['username'] ) && isset( $_POST['password'] ) ){
			//TODO: verify user and check for sql injection
			echo $_POST['username'];
			$account = Account::login($_POST['username'],$_POST['password']);
			if( $account === false){
				echo "An error occurred";
			}
			else
				echo "Login success";
		}

	}
	

	$smarty->assign('status','success');

	$smarty->display('account/login.tpl');
?>
