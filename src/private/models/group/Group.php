<?php
require_once $MODELS.'species/Species.php';
require_once $MODELS.'organization/Organization.php';

class Group {
  var $id;
  var $name;
  var $start;
  var $end;
  var $count;
  var $species;
  var $org;

  public function __construct($id,$name,$start,$end,$count,$species,$org){
    $this->id = $id;
    $this->name = $name;
    $this->start = $start;
    $this->end = $end;
    $this->count = $count;
    $this->species = $species;
    $this->org = $org;
  }

  public static function calcEndDate($start,$groupLength){
	  return date('Y-m-d', strtotime($start. ' + ' . $groupLength . ' days') );
  }

  public static function update($id, $name, $start, $groupLength, $count, $speciesId, $org){

	  $end = Group::calcEndDate($start, $groupLength);

	  $sql = sprintf(
	  "UPDATE `group`
	  SET
	  	group_name='%s', group_start='%s', group_end='%s', group_count='%d', group_species_id='%d'
	  WHERE ( group_id = '%d' AND group_org_id = '%d');",
	  $name,
	  $start,
	  $end,
	  $count,
	  $speciesId,
	  $id,
	  $org->id
  	  );

	  $result = query_first($sql);
	  if( $result === false){
		  return false;
	  }
	  else{
		  return new Group($id,$name,$start,$end,$count,$speciesId,$org);
	  }
  }

	public static function updateEndDates($speciesId,$org){

		$sql = sprintf(
			"SELECT
				`group_id`,`group_start`
			FROM
				`group`
			WHERE
				`group_species_id`='%d' AND `group_org_id`='%d';",
			$speciesId,
			$org->id
		);

		$result = query_array($sql);

		$success = true;
		for($i = 0; $i < count($result); $i++){

			$groupLength = StageOrder::getGroupLength($speciesId,$org);

			$end_date = Group::calcEndDate($result[$i]['group_start'], $groupLength);

			$sql = sprintf(
				"UPDATE `group`
				SET
					`group_end`='%s'
				WHERE
					`group_id`='%d' AND `group_org_id`='%d' AND `group_species_id`='%d';",
				escape_str($end_date),
				$result[$i]['group_id'],
				$org->id,
				$speciesId
			);

			$success = query_first($sql);
			if( $success == false)
				return $success;
		}
		return $success;
	}

  public static function createGroup($name,$start,$groupLength,$count,$speciesId,$org){

	$end = Group::calcEndDate($start, $groupLength);

    $sql = sprintf(
      "INSERT INTO `group` (`group_name`,`group_start`,`group_end`,`group_count`,
        `group_species_id`,`group_org_id`)
      VALUES ('%s','%s','%s','%d','%d','%d');",
      escape_str($name),
      escape_str($start),
	  escape_str($end),
      $count,
      $speciesId,
      $org->id
    );

    $result = query_first($sql);
    if( $result === true){
      $sql = "SELECT LAST_INSERT_ID();";
      $result = query_first($sql);
      $group = new Group($result['LAST_INSERT_ID()'],$name,$start,$end,$count,$speciesId,$org);
      return $group;
    }
    else {
      return false;
    }

  }

  public static function getGroup($id, $org){
	  $sql = sprintf("SELECT `group_name`,`group_start`,`group_end`,`group_count`,`group_species_id`
	  FROM `group`
	  WHERE ( group_id = '%d' AND group_org_id = '%d');",
	  $id,
	  $org->id
  	  );

	  $result = query_first($sql);
	  if( $result === false){
		  return false;
	  }
	  else{
		  return new Group($id,$result['group_name'],$result['group_start'],
		  	$result['group_end'],$result['group_count'],$result['group_species_id'],$org);
	  }
  }

  public static function getGroupsInRange($start,$end,$org){
	  $sql = sprintf("SELECT *
	  FROM `group`
	  WHERE
	  	(
			( group_start >= '%s' AND group_start <= '%s' )
			OR
			( group_end >= '%s' AND group_end <= '%s')
			OR
			( group_start <= '%s' AND group_end >= '%s')
		)
		AND group_org_id = '%d'
		;",
		escape_str( $start ),
		escape_str( $end ),
		escape_str( $start ),
		escape_str( $end ),
		escape_str( $start ),
		escape_str( $end ),
		$org->id
		);

		$result = query_array($sql);

		return $result;

  }

}
?>
