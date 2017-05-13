<?php
class EmailKey {
	var $account;
	var $key;

	public function __construct($account,$key){
		$this->account = $account;
		$this->key = $key;
	}

	/**
	 * Creates a new key for email verification for this account
	 * @param Account - key should be created for
	 * @return EmailKey - email key that has been stored in database
	 */
	public static function createNewEmailKey($account){

		//creates hash as key
		$key = md5( $account->username . $account->email . date('mYis') );

		$sql = sprintf("INSERT INTO `email_keys`
			(`account_id`,`key`)
			VALUES ('%d','%s');",
			$account->id,
			escape_str($key)
		);

		$result = query_first($sql);
		if( $result === true ){
			return new EmailKey($account,$key);
		}
		else {
			return false;
		}

	}

	/**
	 * Get Account Associated with email/key pair
	 * @param email - String
	 * @param key 	- String hexdec
	 * @return id of account with email and key
	 */
	public static function verify($email,$key){
		$sql = sprintf("SELECT
				account.account_id, count(*) AS count
			FROM `account`
			JOIN `email_keys` AS k
			WHERE account_email = '%s'AND k.key = '%s';",
			escape_str($email),
			escape_str($key)
		);

		$result = query_first($sql);
		if( $result === false ){
			return false;
		}
		else{
			if( $result['count'] == 1){
				return $result['account_id'];
			}
			else{
				return false;
			}
		}
	}
}
?>