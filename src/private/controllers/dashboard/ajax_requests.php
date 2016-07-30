<?php

  if( !isset($_POST['ajax_request']) || $_POST['ajax_request'] !== 'true'){
    $data = array('error' => 'Not an ajax request');
    json_response($data);
  }

  require $MODELS.'db_connect.php';
  require $MODELS.'account/Account.php';
  require $MODELS.'role/Role.php';
  require $MODELS.'stage/Stage.php';

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
    $data = array_merge( $data , createStage($_POST['stageName'], $_POST['stageLength'], $role) );
  }
  if( isset($_POST['getStages']) && $_POST['getStages'] === 'true'){
    $data = array_merge($data, getStages($role) );
  }
  if( isset( $_POST['selectStage'] ) && $_POST['selectStage'] === 'true' ){
     json_response(array('selectStages' => 'yayyyy'));
  }

  db_close();
  json_response($data);

  function createStage($name,$length,$role){


    $stage = Stage::createStage($name,$length,$role->org);

    if( $stage === false){
      return array('error' => 'true', 'createStageError' => 'createStage cred');
    }
    else{
      return array('createStage' => 'success',
       'stageName' => $stage->name, 'stageLength' => $stage->length);
    }
  }

  function getStages($role){
    $stageList = Stage::getStageList($role->org);
    if( $stageList === false){
      return array('error' => 'true', 'getStagesError' => 'failure');
    }
    else{
      return array('stageList' => json_encode( $stageList ) );
    }
  }
?>
