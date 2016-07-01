<?php
$LOCAL_CONFIG = true;


$urlTokens = explode( '/',$_SERVER['REQUEST_URI'] );

if( strcmp( $urlTokens[1],  'livestocktracker') != 0){
  echo "</br>404 livestocktracker";
  die();
}

$request_url = "";
for($i = 2; $i < count($urlTokens); $i++){
  $request_url = $request_url . "/" . $urlTokens[$i];
}

if( strcmp( $request_url, '') == 0){
  $request_url = '/home';
}

$_GET['_url'] = $request_url;

require '../src/launch.php';
?>
