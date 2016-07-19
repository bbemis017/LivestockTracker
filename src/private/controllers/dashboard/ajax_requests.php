<?php

  echo "AJAX REQUEST";
  print_r( $_POST );
  if( !isset($_POST['ajax_request']) || $_POST['ajax_request'] !== 'true'){
    $data = array('error' => 'Not a json response');
    json_response($data);
  }

  if( isset($_POST['createStage'] && $_POST['createStage'] === 'true') ){
    createStage($_POST['stageName'],$_POST['stageLength']);
  }

  function createStage($name,$length){
    
  }
?>
