<?php

	require "../config/constants.php";

	require "/usr/lib/php/7.0/Smarty/Smarty.class.php";

	require $CONFIG_DIR . 'routes.php';
	$current_url = $_GET['_url'];

	ini_set('display_errors',true);
	error_reporting(E_ALL + E_NOTICE);
	

	$smarty = new Smarty();

	$smarty->setTemplateDir("private/views/templates");
	$smarty->setCompileDir("private/views/smarty");
	$smarty->setCacheDir("private/views/cache");
	$smarty->setConfigDir("private/views/conf");

//	phpinfo();

	//check if route is defined
	if( isset( $ROUTE[$current_url] ) ){
		//call controller action
		//require $MODELS.$DATABASE;
		require $CONTROLLERS . $ROUTE[$current_url];
	}
	else{
		//display 404 page
		echo "404";
	}
?>
