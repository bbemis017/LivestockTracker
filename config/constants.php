<?php
//ip address
$IP = $_SERVER['HTTP_HOST'];
$HOST = "http://" . $IP . '/livestocktracker';

$VERSION = '0.4.1 Alpha';

//directories
$ROOT_DIR = __DIR__ . "/../";
$CONFIG_DIR = $ROOT_DIR . "config/";
$SCRIPTS = $ROOT_DIR . "scripts/";
$SRC_DIR = $ROOT_DIR . "src/";
$CONTROLLERS = $SRC_DIR . "private/controllers/";
$MODELS = $SRC_DIR . "private/models/";
$VIEWS = $SRC_DIR . "private/views/";
$MODULE_DIR = $SRC_DIR . "private/modules/";

//authorization
$AUTH = array(
  "viewer",
  "user",
  "owner",
  "super"
);

//different types of keys for email_keys table
$KEY_TYPE = array(
	"verification" => 0,
	"password_reset" => 1
);

//method to use to connect to database
$DATABASE = "mysql_connect.php";

?>
