<?php

require "../config/constants.php";

require "/usr/lib/php/7.0/Smarty/Smarty.class.php";

require $CONFIG_DIR . 'routes.php';
$current_url = $_GET['_url'];

//display errors that occur in this page
error_reporting(E_ALL|E_STRICT); ini_set('display_errors', true);

$smarty = new Smarty();

$smarty->setTemplateDir($VIEWS.'templates');
$smarty->setCompileDir($VIEWS.'smarty');
$smarty->setCacheDir($VIEWS.'cache');
$smarty->setConfigDir($VIEWS.'configs');

$smarty->assign('HOST',$HOST);

//  $smarty->testInstall();
//	phpinfo();
//  die();

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
