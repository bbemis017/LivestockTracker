<?php
require_once $MODELS.'organization/Organization.php';

class Species {
	var $id;
	var $name;
	var $org;

	public function __construct($id,$name,$org){
		$this->id = $id;
		$this->name = $name;
		$this->org = $org;
	}

	public static function createSpecies($name,$org){
		$sql = sprintf("
			INSERT INTO `species` (`species_name`,`species_org_id`)
				VALUES ('%s','%d');",
			escape_str($name),
			$org->id
		);

		$result = query_first($sql);
		if( $result === true ){
			$sql = "SELECT LAST_INSERT_ID();";
			$result = query_first($sql);
			return new Species($result['LAST_INSERT_ID()'],$name,$org->id);
		}
		else{
			return false;
		}
	}

	public static function updateSpecies($id, $name,$org){
		$sql = sprintf("
			UPDATE `species`
			SET `species_name`='%s'
			WHERE `species_id`='%d' AND `species_org_id`='%d';",
			escape_str($name),
			$id,
			$org->id
		);

		$result = query_first($sql);
		if( $result === false){
			return false;
		} else {
			return new Species($id,$name,$org);
		}
	}


	public static function getSpeciesList($org){
		$sql = sprintf("
			SELECT
				species_id, species_name
			FROM
				`species`
			WHERE
				species_org_id = %d
			",
			$org->id
		);

		$result = query_array($sql);

		return $result;
	}

}
?>
