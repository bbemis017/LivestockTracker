<?php
//ip address
$IP = $_SERVER['HTTP_HOST'];
$HOST = $IP . '/livestocktracker';

//directories
$ROOT_DIR = __DIR__ . "/../";
$CONFIG_DIR = $ROOT_DIR . "config/";
$SRC_DIR = $ROOT_DIR . "src/";
$CONTROLLERS = $SRC_DIR . "private/controllers/";
$MODELS = $SRC_DIR . "private/models/";
$VIEWS = $SRC_DIR . "private/views/";

//method to use to connect to database
$DATABASE = "mysql_connect.php";

//local settings for non-production
if( isset($LOCAL_CONFIG) ){
  require 'local_constants.php';
}


?>
