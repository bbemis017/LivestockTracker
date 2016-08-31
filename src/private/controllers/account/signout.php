<?php

session_start();

if( isset( $_SESSION['userkey'] ) ){
	$_SESSION = array();
	session_destroy();
	redirect_url('/account/login');
}

?>
