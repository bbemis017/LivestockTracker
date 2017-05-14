<?php
	$ROUTE = array(
		"/home" => "account/login.php",

		"/account/login" => "account/login.php",
		"/account/signup" => "account/signup.php",
		"/account/signout" => "account/signout.php",
		"/account/verification" => "account/verification.php",
		"/account/reset_email" => "account/reset_email.php",
		"/account/reset_password" => "account/reset_password.php",

		"/dashboard" => "dashboard/show.php",
		"/dashboard/ajax/" => "dashboard/ajax_requests.php"
	);

	if( active_module("Auto_Deploy") ){
		$ROUTE["/autodeploy911live/deploy"] = "../modules/Auto_Deploy/deploy.php";
	}

	function redirect_url($str){
		global $HOST;
		header('Location: ' . $HOST . $str);
		exit;
	}

	function json_response($data){
		header('Content-Type: application/json');
		echo json_encode($data);
		exit();
	}
?>
