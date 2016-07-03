<?php
	$ROUTE = array(
		"/home" => "account/login.php",

		"/account/login" => "account/login.php",
		"/account/signup" => "account/signup.php",
		"/dashboard" => "dashboard/show.php"
	);

	function redirect_url($str){
		global $HOST;
		header('Location: ' . $HOST . $str);
		exit;
	}
?>
