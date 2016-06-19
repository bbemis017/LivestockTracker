<?php
class Account {
	var $username;
	var $email;
	var $active;

	public function Account($username,$email,$active){
		$this->username = $username;
		$this->email = $email;
		$this->active = $active;
	}

	public static function login($username,$password){
		$sql = sprintf(
			"SELECT
			 `account_username`,`account_email`,`account_active`
			 FROM
				 `account`
			 WHERE 
				( ( `account_username` = '%s' OR `account_email` = '%s')  AND `account_password` = '%s');",
			escape_str($username),
			escape_str($username),
			crypt($password)
		);

		$result = query_first($sql);
		if( $result === false ){
			return false;
		}
		else{
			$account = new Account( $result['account_username'], $result['account_email'], $result['account_active'] );
			return $account;
		}
				
	}

	public static function createAccount($username,$email,$password) {
		$sql = sprintf("INSERT INTO ".
			"`account` ".
			"(`account_username`,`account_password`,`account_email`,`account_active`) ".
			"VALUES ('%s','%s','%s','0');",
			escape_str($username),
			crypt($password),
			escape_str($email)
		); 

		$result = query_first($sql);
		if( $result === true){
			return new Account($username, $email, 0 );
		}
		else{
			return false;
		}
	}
}
?>
