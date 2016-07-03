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

  public static function createGroup($name,$start,$count,$species,$org){
    //TODO: calculate an ending date for the group
    $end = "June 21";

    $sql = sprintf(
      "INSERT INTO (`group_name`,`group_start`,`group_end`,`group_count`,
        `group_species_id`,`group_org_id`)
      VALUES ('%s','%s','%s','%d','%d','%d');",
      escape_str($name),
      escape_str($start),
      $count,
      $species->id,
      $org->id
    );

    $result = query_first($sql);
    if( $result === true){
      $sql = "SELECT LAST_INSERT_ID();";
      $result = query_first($sql);
      $group = new Group($result['LAST_INSERT_ID()'],$name,$start,$end,$count,
        $species,$org);
      return $group;
    }
    else {
      return false;
    }

  }

}
?>
