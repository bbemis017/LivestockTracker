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
  require_once $MODELS.'group/Group.php';

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

if( isset($_POST['calendarData']) && $_POST['calendarData'] === 'true' ){
	$data = array_merge($data, calendarData($_POST['calendar_start'], $_POST['calendar_end'], $role) );
}
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
if( isset( $_POST['createGroup'] ) && $_POST['createGroup'] === 'true'){
	$data = array_merge( $data ,
		createGroup( $_POST['groupName'], $_POST['groupSize'], $_POST['groupStart'], $_POST['groupSpecies'], $role )
	);
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

  function createGroup($name, $size, $start, $speciesId, $role){

	  $stageList = StageOrder::getStages( $speciesId, $role->org );

	  $groupLength = 0;
	  for( $i = 0; $i < count( $stageList ); $i++){
		  $groupLength += intval( $stageList[$i]['stage_length'] );
	  }

	  $group = Group::createGroup( $name, $start, $groupLength, $size, $speciesId, $role->org);

	  if( $group === false){
		  return array('error' => 'true', 'createGroupError' => 'failure');
	  }
	  else{
		  return array('createGroup' => 'true' ,'groupId' => $size,
	  		'groupName' => $name );
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

  function calendarData($start,$end,$role){

	  $events = array();
	  $groups = Group::getGroupsInRange($start,$end,$role->org);

	  for($i = 0; $i < count($groups); $i++){

		  $group_start = $groups[$i]['group_start'];
		  $group_name = $groups[$i]['group_name'];

		  $stages = StageOrder::getStages( intval( $groups[$i]['group_species_id'] ), $role->org );

		  $lastDays = 0;
		  $days = 0;
		  for($j = 0; $j < count($stages); $j++){

			  //get days till start of stage and end of stage
			  $lastDays = $days;
			  $days += intval( $stages[$j]['stage_length'] );

			  $stage_start = date('Y-m-d', strtotime($group_start . ' + ' . $lastDays . ' days') ) . '';
			  $stage_end = date('Y-m-d', strtotime($group_start . ' + ' . $days . ' days') );

			  $title = $group_name . ': '. $stages[$j]['stage_name'] . ': ' . $groups[$i]['group_count'];

			  $event = array('title' => $title, 'date_start' => $stage_start, 'date_end' => $stage_end );
			  array_push( $events, $event );

		  }


	  }

	  return array('result' => $events );
  }
?>
