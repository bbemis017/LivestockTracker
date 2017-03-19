<?php
  if( !isset($_POST['ajax_request']) || $_POST['ajax_request'] !== 'true'){
    $data = array('error' => 'Not an ajax request');
    json_response($data);
  }

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
if( isset( $_POST['getSpeciesStageList'] ) && $_POST['getSpeciesStageList'] === 'true'){
	$data = array_merge($data, getSpeciesStages($role,$_POST['speciesId']));
}
if( isset($_POST['getSpecies']) && $_POST['getSpecies'] === 'true'){
	$data = array_merge($data, getSpecies($role) );
}
if( isset($_POST['createStage']) && $_POST['createStage'] === 'true') {
	$data = array_merge( $data , createStage($_POST['stageName'], $_POST['stageLength'], $role) );
}
if( isset($_POST['editStage']) && $_POST['editStage'] === 'true') {
	$data = array_merge( $data, editStage($_POST['stage_id'],$_POST['stageName'], $_POST['stageLength'], $role) );
}
if( isset( $_POST['selectStage'] ) && $_POST['selectStage'] === 'true' ){
	if( isset( $_POST['createSpecies'] ) && $_POST['createSpecies'] === 'true') {
			//json_response(array('selectStages' => 'yayyyy'));
			$data = array_merge($data, createSpecies($_POST['speciesName'], $_POST['stages'], $role ) );
	}
	else if( isset( $_POST['editSpecies'] ) && $_POST['editSpecies'] === 'true') {
			$data = array_merge($data, updateSpecies($_POST['speciesId'], $_POST['speciesName'], $_POST['stages'], $role) );
	}
}
if( isset( $_POST['createGroup'] ) && $_POST['createGroup'] === 'true'){
	$data = array_merge( $data ,
		createGroup( $_POST['groupName'], $_POST['groupSize'], $_POST['groupStart'], $_POST['groupSpecies'], $role ) );
}
if( isset( $_POST['editGroup']) && $_POST['editGroup'] === 'true'){
	$data = array_merge( $data,
		updateGroup( $_POST['groupId'], $_POST['groupName'], $_POST['groupSize'], $_POST['groupStart'], $_POST['groupSpecies'], $role )
	);
}
if( isset( $_POST['getGroup'] ) && $_POST['getGroup'] === 'true'){
	$data = array_merge( $data, getGroup($_POST['groupId'], $role) );
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

function editStage($id,$name,$length,$role){

	$stage = Stage::updateStage($id,$name,$length,$role->org);

	if( $stage === false){
		return array('error' => 'true', 'updateStageError' => 'editStage cred');
	} else {

		//get species that use this stage
		$related_species = StageOrder::getSpecies($stage->id,$role->org);

		if( $related_species === false){
			return array('error' => 'true', 'getSpeciesError' => 'unknown');
		} else {

			//update the end dates for any group that had a modified stage
			for($i = 0; $i < count($related_species); $i++){

				$result = Group::updateEndDates($related_species[$i]['stage_order_species_id'],$role->org);

				//something went wrong
				if( $result == false){
					return array('error' => 'true', 'updateEndDatesError' => 'unknown');
				}

			}

			return array('editStage' => 'success', 'stageId' => $stage->id,
				'stageName' => $stage->name, 'stageLength' => $stage->length);
		}

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

	function updateSpecies($id, $name,$stages,$role){

		$stages = json_decode($stages, true);

		$species = Species::updateSpecies($id, $name, $role->org);

		if( $species == false){
			return array('error' => 'true', 'updateSpeciesError' => 'failure');
		}
		else {

			$order = StageOrder::updateOrders($species,$stages,$role->org);

			if( $order === true){
				$group_results = Group::updateEndDates($id,$role->org);

				return array('updatedSpecies' => 'success', 'speciesId' => $species->id,
					'speciesName' => $species->name, 'stages' => json_encode($stages), 'groupUpdateSuccess' => json_encode($group_results) );
			}
			else{
				return array('error' => 'true', 'updateStageOrder' => 'failure', 'warning' => 'data may have been lost');
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

	function getSpeciesStages($role,$speciesId){
		$stageList = StageOrder::getStages($speciesId, $role->org);
		if( $stageList === false){
			return array('error' => 'true', 'getSpeciesStagesError' => 'failure');
		}
		else {
			return array('speciesStageList' => json_encode( $stageList) );
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

  function randomColor(){
	$color='rgb(';
	for($i = 0; $i < 3; $i++){
		$color .= rand(0,255);
		if($i < 2){
			$color .= ',';
		}
	}
	$color .= ')';
	return $color;
  }

  function calendarData($start,$end,$role){

	  $events = array();
	  $groups = Group::getGroupsInRange($start,$end,$role->org);

	  for($i = 0; $i < count($groups); $i++){

		  $group_start = $groups[$i]['group_start'];
		  $group_name = $groups[$i]['group_name'];
		  $group_color = randomColor();

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

			  $event = array('id' => $groups[$i]['group_id'], 'title' => $title,
			   'date_start' => $stage_start, 'date_end' => $stage_end, 'color' => $group_color);
			  array_push( $events, $event );

		  }
	  }

	  return array('result' => $events );
  }

  function getGroup($id, $role){

	  $group = Group::getGroup($id,$role->org);
	  if( $group === false){
		  return array('error' => 'true', 'getGroup' => 'failure');
	  }
	  else{
		  return array('groupId' => $group->id, 'groupName' => $group->name, 'groupStart' => $group->start,
		   'groupSize' => $group->count, 'groupSpecies' => $group->species);
	  }
  }

  function updateGroup($id, $name, $size, $start, $speciesId, $role){

	  $stageList = StageOrder::getStages( $speciesId, $role->org );

	  $groupLength = 0;
	  for( $i = 0; $i < count( $stageList ); $i++){
		  $groupLength += intval( $stageList[$i]['stage_length'] );
	  }

	  $group = Group::update($id,$name,$start,$groupLength, $size, $speciesId, $role->org);
	  if( $group === false){
		  return array('error' => 'true', 'updateGroup' => 'failure');
	  }
	  else {
		  return array('updateGroup' => 'true');
	  }
  }
?>
