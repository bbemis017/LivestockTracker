<?php
class Account {
	var $id;
	var $username;
	var $email;
	var $active;

	public function __construct($id,$username,$email,$active){
		$this->id = $id;
		$this->username = $username;
		$this->email = $email;
		$this->active = $active;
	}

	public function loginSession(){
		session_start();
		$_SESSION['userkey'] = $this->id;
	}

	public static function login($username,$password){
		$sql = sprintf(
			"SELECT
			 `account_id`,`account_username`,`account_email`,`account_active`,`account_password`
			 FROM
				 `account`
			 WHERE
				( `account_username` = '%s' OR `account_email` = '%s');",
			escape_str($username),
			escape_str($username)
		);

		$result = query_first($sql);

		if( $result === false ){
			return false;
		}
		else{
			if( password_verify($password, $result['account_password'])){
				$account = new Account( $result['account_id'], $result['account_username'], $result['account_email'], $result['account_active'] );
				$account->loginSession();
				return $account;
			}
			else{
				return false;
			}

		}

	}

	public static function createAccount($username,$email,$password) {
		$sql = sprintf("INSERT INTO ".
			"`account` ".
			"(`account_username`,`account_password`,`account_email`,`account_active`) ".
			"VALUES ('%s','%s','%s','0');",
			escape_str($username),
			password_hash($password,PASSWORD_BCRYPT),
			escape_str($email)
		);

		$result = query_first($sql);
		if( $result === true){
			$sql = "SELECT LAST_INSERT_ID();";
			$result = query_first($sql);
			$account = new Account($result['LAST_INSERT_ID()'], $username, $email, 0 );
			$account->loginSession();
			return $account;
		}
		else{
			return false;
		}
	}

	public static function loggedUserId(){
		session_start();
		if( !isset($_SESSION['userkey']))
			return -1;
		return $_SESSION['userkey'];
	}

	public static function getAccount(){
		$id = Account::loggedUserId();

		if( $id == -1)
			return false;

		$sql = sprintf("SELECT
				`account_username`,`account_email`,`account_active`
			FROM
				`account`
			WHERE
				`account_id` = '%d';",
			$id);

		$result = query_first($sql);

		if( $result === false){
			return false;
		}
		else{
			return new Account($id, $result['account_username'],$result['account_email'],$result['account_active']);
		}

	}
}
?>
