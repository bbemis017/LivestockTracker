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

				if( active_module("mail_ses") ){
					require $MODULE_DIR . 'mail_ses/email.php';

					$template = $MODULE_DIR . 'mail_ses/verification_email.tpl';

					$result = sendVerificationEmail($account->account->email,$emailKey->key,"Verify your email", $template);
				}
				else{
					$result = true;
				}

				if( $result == true){
					redirect_url('/dashboard');
				}
				else{
					echo "email failed to send";
				}

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
