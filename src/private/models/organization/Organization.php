<?php
class Organization {
  var $id;
  var $name;

  public function __construct($id,$name){
    $this->id = $id;
    $this->name = $name;
  }

  public static function createOrganization($name){
    $sql = sprintf("INSERT INTO
              `organization` (org_name)
            VALUES
              ('%s');",
            escape_str($name)
    );
    $result = query_first($sql);
    if( $result === true ){
      $sql = "SELECT LAST_INSERT_ID();";
      $result = query_first($sql);
      return new Organization($result['LAST_INSERT_ID()'],$name);
    }
    else {
      return false;
    }
  }

}
?>
