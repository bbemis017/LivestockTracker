<?php


if(  $_SERVER['REQUEST_METHOD'] === 'POST' ){
  if( isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['email'] )
      && isset( $_POST['password2'] ) && isset( $_POST['organization'] ) ) {

    require $MODELS.'db_connect.php';
    require $MODELS.'account/Account.php';
    require $MODELS.'organization/Organization.php';

    if( strcmp( $_POST['password'] , $_POST['password2'] == 0) ){
      $account = Account::createAccount( $_POST['username'], $_POST['email'], $_POST['password'] );
      if( $account === false ){
        echo "an error occurred";
      }
      else {
        $org = Organization::createOrganization( $_POST['organization'] );
        echo "signup successful!!!";
      }

      echo "some stuff";
    }
    else{
      echo "passwords don't match";
    }

    db_close();

  }
}

$smarty->display("account/signup.tpl");

?>
