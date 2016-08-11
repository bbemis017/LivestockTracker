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

}
?>
