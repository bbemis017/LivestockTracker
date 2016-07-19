<?php
	$ROUTE = array(
		"/home" => "account/login.php",

		"/account/login" => "account/login.php",
		"/account/signup" => "account/signup.php",

		"/dashboard" => "dashboard/show.php",
		"/dashboard/ajax/" => "dashboard/ajax_requests.php"
	);

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
