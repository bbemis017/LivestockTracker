<?php
require_once $MODELS.'organization/Organization.php';
class Stage {
  var $id;
  var $name;
  var $length;
  var $org;

  public function __construct($id,$name,$length,$org){
    $this->id = $id;
    $this->name = $name;
    $this->length = $length;
    $this->org = $org;
  }

  public static function createStage($name,$length,$org){
    $sql = sprintf(
      "INSERT INTO `stage` (`stage_name`,`stage_length`,`stage_org_id`)
      VALUES ('%s','%d','%d');",
      escape_str($name),
      $length,
      $org->id
    );

    $result = query_first($sql);
    if($result === true){
      $sql = "SELECT LAST_INSERT_ID();";
      $result = query_first($sql);
      return new Stage($result['LAST_INSERT_ID()'],$name,$length,$org->id);
    }
    else{
      return false;
    }

  }

	public static function updateStage($id,$name,$length,$org){
		$sql = sprintf(
			"UPDATE `stage`
			SET `stage_name`='%s',`stage_length`='%d'
			WHERE `stage_id`='%d' AND `stage_org_id`='%d';",
			escape_str($name),
			$length,
			$id,
			$org->id
		);

		$result = query_first($sql);
		if($result === true){
			return new Stage($id,$name,$length,$org);
		} else {
			return false;
		}
	}

  public static function getstageList($org){
    $sql = sprintf(
      "SELECT
        stage_id, stage_name, stage_length
      FROM
        `stage`
      WHERE
        stage_org_id = %d;",
      $org->id
    );

    $result = query_array($sql);

    return $result;
  }

}
?>
