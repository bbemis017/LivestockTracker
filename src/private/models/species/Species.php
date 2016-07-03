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

  public function static createSpecies($name,$org){
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

}
?>
