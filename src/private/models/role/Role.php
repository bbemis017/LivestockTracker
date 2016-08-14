<?php
require_once $MODELS.'organization/Organization.php';
require_once $MODELS.'account/Account.php';

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

  public static function getFirstRole($account){
    $sql = "SELECT
	     organization.org_id, organization.org_name, role.role_auth
     FROM
	    `organization`
     INNER JOIN
	    `role`
     ON
	    organization.org_id=role.role_org_id
     WHERE
      role.role_account_id='$account->id'
    ;";
    $result = query_first($sql);
    if( $result === false){
      return false;
    }
    else{
      $org = new Organization( (int) $result['org_id'], $result['org_name'] );
      return new Role($account, $org, (int) $result['role_auth'] );
    }

  }
}
?>
