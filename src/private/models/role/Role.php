<?php
class Role {
  var $account;
  var $org;
  var $auth;

  public function __construct($account,$org,$auth){
    $this->account = $account;
    $this->org = $org;
    $this->auth = $auth;
  }

  public static function createRole($account,$org,$auth){
    $sql = sprintf(
      "INSERT INTO `role` (`role_account_id`,`role_org_id`,`role_auth`)
      VALUES ('%d','%d','%d');",
      $account->id,
      $org->id,
      $auth
    );

    $result = query_first($sql);
    if( $result === true){
      return new Role($account,$org,$auth);
    }
    else{
      echo "failed";
      die();
      return false;
    }
  }
}
?>
