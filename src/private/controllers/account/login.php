<?php
	//if request is post than a user is trying to login
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){

    //require $MODELS.'db_connect.php';
    require $MODELS.'account/Account.php';

		if( isset( $_POST['username'] ) && isset( $_POST['password'] ) ){
			//verify user and check for sql injection
			$account = Account::login($_POST['username'],$_POST['password']);
			if( $account === false){
				echo "An error occurred";
			}
			else{
				echo "Login success";
        redirect_url('/dashboard');
      }
		}

    db_close();

	}

	$smarty->assign('status','success');

	$smarty->display('account/login.tpl');
?>
