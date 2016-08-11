<?php

  if( !isset($_POST['ajax_request']) || $_POST['ajax_request'] !== 'true'){
    $data = array('error' => 'Not an ajax request');
    json_response($data);
  }

  require $MODELS.'db_connect.php';
  require $MODELS.'account/Account.php';
  require_once $MODELS.'role/Role.php';
  require_once $MODELS.'stage/Stage.php';
  require_once $MODELS.'species/Species.php';
  require_once $MODELS.'stageOrder/StageOrder.php';

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

if( isset($_POST['getStages']) && $_POST['getStages'] === 'true'){
	$data = array_merge($data, getStages($role) );
}
if( isset($_POST['getSpecies']) && $_POST['getSpecies'] === 'true'){
	$data = array_merge($data, getSpecies($role) );
}
if( isset($_POST['createStage']) && $_POST['createStage'] === 'true') {
	$data = array_merge( $data , createStage($_POST['stageName'], $_POST['stageLength'], $role) );
}
if( isset( $_POST['selectStage'] ) && $_POST['selectStage'] === 'true' ){
	if( isset( $_POST['createSpecies'] ) && $_POST['createSpecies'] === 'true') {
			//json_response(array('selectStages' => 'yayyyy'));
			$data = array_merge($data, createSpecies($_POST['speciesName'], $_POST['stages'], $role ) );
	}
}


  db_close();
  json_response($data);

  function createStage($name,$length,$role){


    $stage = Stage::createStage($name,$length,$role->org);

    if( $stage === false){
      return array('error' => 'true', 'createStageError' => 'createStage cred');
    }
    else{
      return array('createStage' => 'success','stageId' => $stage->id,
       'stageName' => $stage->name, 'stageLength' => $stage->length);
    }
  }

  function createSpecies($name,$stages,$role){

	  $stages = json_decode($stages, true);

	  $species = Species::createSpecies($name,$role->org);

	  if( $species === false){
		  return array('error' => 'true', 'createSpeciesError' => 'failure');
	  }
	  else{

		  $order = StageOrder::createOrders($species,$stages,$role->org);

		  if( $order === true){
			  return array('createSpecies' => 'success', 'speciesId' => $species->id,
		  		'speciesName' => $species->name, 'stages' => json_encode($stages) );
		  }
		  else {
			  return array('error' => 'true', 'createStageOrder' => 'failure');
		  }

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

  function getSpecies($role){
	$speciesList = Species::getSpeciesList($role->org);
	if( $speciesList === false){
		return array('error' => 'true', 'getSpeciesError' => 'failure');
	}
	else{
		return array('speciesList' => json_encode( $speciesList ) );
	}
  }
?>
