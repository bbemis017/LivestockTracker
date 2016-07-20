<?php

  if( !isset($_POST['ajax_request']) || $_POST['ajax_request'] !== 'true'){
    $data = array('error' => 'Not a json response');
    json_response($data);
  }

  require $MODELS.'db_connect.php';
  require $MODELS.'account/Account.php';
  require $MODELS.'role/Role.php';

  $account = Account::getAccount();
  if( $account === false){
    $data = array('error' => 'Not logged in');
    db_close();
    json_response($data);
  }

  $role = Role::getFirstRole($account);
  if( $role === false){
    $data = array('error' => 'Not Authorized');
    db_close();
    json_response($data);
  }

 $data = array();

  if( isset($_POST['createStage']) && $_POST['createStage'] === 'true') {
    $data = createStage($_POST['stageName'],$_POST['stageLength'],$role);
  }

  json_response($data);

  function createStage($name,$length,$role){
    $data = array('success' => 'createStage');
    return $data;
  }
?>
