<?php
	//if request is post than a user is trying to login
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){

		require $MODELS.'account/Account.php';

		if( isset( $_POST['login'] ) ){
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
		}
		else if( isset($_POST['password_reset'] ) ){

			$url = "/account/reset_email";
			if( isset($_POST['username']) ){
				$url = $url . "?user=" . $_POST['username'];
			}
			redirect_url($url);
		}

		db_close();

	}

	if( active_module("sendmail") ){
		$show_pass_reset = true;
	}
	else{
		$show_pass_reset = false;
	}

	$smarty->assign('show_pass_reset', $show_pass_reset);
	$smarty->display('account/login.tpl');
?>
