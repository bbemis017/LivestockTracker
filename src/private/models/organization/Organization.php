<?php
class Organization {
  var $name;

  public function Organization($name){
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
      return new Organization($name);
    }
    else {
      return false;
    }
  }

}
?>
