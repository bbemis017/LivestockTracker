<?php
require_once $MODELS.'species/Species.php';
require_once $MODELS.'stage/Stage.php';
require_once $MODELS.'organization/Organization.php';

class StageOrder {
  var $species;
  var $stage;
  var $rank;
  var $org;

  public function __construct($species,$stage,$rank,$org){
    $this->species = $species;
    $this->stage = $stage;
    $this->rank = $rank;
    $this->org = $org;
  }

  public static function createStageOrder($species,$stage,$rank,$org){
    $sql = sprintf(
      "INSERT INTO
        (`stage_order_species_id`,`stage_order_stage_id`,`stage_order_rank`,
        `stage_order_org_id`),
      VALUES ('%d','%d','%d','%d');",
      $species->id,
      $stage->id,
      $rank,
      $org->id
    );

    return new StageOrder($species,$stage,$rank,$org);
  }

  public static function createOrders($species,$rankList,$org){
	$sql = "INSERT INTO
		`stage_order` (`stage_order_species_id`,`stage_order_stage_id`,`stage_order_rank`,`stage_order_org_id`)
	VALUES ";
	for( $i = 0; $i < count($rankList); $i++){
		$value = sprintf("(%d,%s,%d,%d)",$species->id,escape_str($rankList[$i]),$i,$org->id);
		if( $i == count($rankList) - 1){
			$value .= ";";
		}
		else{
			$value .= ",";
		}
		$sql .= $value;
	}

	$result = query_first($sql);
	if( $result === true){
		return true;
	}
	else{
		return false;
	}

  }

	public static function updateOrders($species,$rankList,$org){
		$sql = sprintf("DELETE FROM
				`stage_order`
			WHERE
				`stage_order_species_id`='%d' AND `stage_order_org_id`='%d'",
			$species->id,
			$org->id
		);
		$result = query_first($sql);
		if($result === true){
			return StageOrder::createOrders($species,$rankList,$org);
		}
		else{
			return false;
		}
	}

	public static function getGroupLength($speciesId, $org){
		$stageList = StageOrder::getStages( $speciesId, $org);

		$groupLength = 0;
		for($i = 0; $i < count( $stageList ); $i++){
			$groupLength += intval( $stageList[$i]['stage_length'] );
		}
		return $groupLength;
	}

	/**
	 * Get all species that have stageId
	 * @param stageId stage that we are matching
	 */
	public static function getSpecies($stageId, $org){
		$sql = sprintf(
			"SELECT DISTINCT `stage_order_species_id`
			FROM `stage_order`
			WHERE `stage_order_stage_id`='%d' AND `stage_order_org_id`='%d';",
			$stageId,
			$org->id
		);

		$result = query_array($sql);
		if( $result === false){
			return false;
		} else {
			return $result;
		}
	}

  public static function getStages( $speciesId, $org){
	$sql = sprintf(
	"SELECT stage_order.stage_order_rank, stage.stage_id, stage.stage_name, stage.stage_length
	FROM `stage_order`
	INNER JOIN `stage`
	ON stage_order.stage_order_stage_id = stage.stage_id
	WHERE stage_order_species_id = %d AND stage_order_org_id = %d
	ORDER BY stage_order.stage_order_rank;",
	$speciesId,
	$org->id
	);

	$result = query_array($sql);

	if( $result === false){
		return false;
	}
	else{
		return $result;
	}

  }

}
?>
