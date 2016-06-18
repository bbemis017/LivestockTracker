<?php

	require "../config/constants.php";

	$current_url = $_GET["_url"];

	echo "_url:".$current_url;

	require $CONFIG_DIR . 'routes.php';

	echo "<br\>";
	//check if route is defined
	if( isset( $ROUTE[$current_url] ) ){
		//call controller action
		//TODO
		echo $ROUTE[$current_url];
	}
	else{
		//display 404 page
		echo "404";
	}
?>
