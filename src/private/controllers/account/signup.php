<?php


if(  $_SERVER['REQUEST_METHOD'] === 'POST' ){
  if( isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['email'] )
      && isset( $_POST['password2'] ) && isset( $_POST['organization'] ) ) {

    require $MODELS.'account/Account.php';
	require $MODELS.'email_keys/EmailKey.php';
    require $MODELS.'organization/Organization.php';
    require $MODELS.'role/Role.php';

	//make sure email doesn't already exist
	if( !Account::emailExists( $_POST['email'] ) ){

		if( strcmp( $_POST['password'] , $_POST['password2'] == 0) ){
			$account = Account::createAccount( $_POST['username'], $_POST['email'], $_POST['password'] );
			if( $account === false ){
				echo "an error occurred";
			}
			else {
				$org = Organization::createOrganization( $_POST['organization'] );
				//give user owner rights to Organization
				$role = Role::createRole($account,$org,2);

				//create and store the verification key
				$emailKey = EmailKey::createNewEmailKey($account, $key_type['verification'] );

				if( active_module("sendmail") ){
					//TODO: send email
				}
				else{
					//log email
					//TODO:
				}

				redirect_url('/dashboard');
			}

		}
		else{
			echo "passwords don't match";
		}
	}
	else{
		echo "email already exists";
	}

    db_close();

  }
}

$smarty->display("account/signup.tpl");

?>
