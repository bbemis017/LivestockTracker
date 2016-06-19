<?php

$db_link = mysqli_connect("localhost","livestock_app","livestock12349876","LivestockTracker");

if( mysqli_connect_errno() ){
	echo "connection to db failed";
	printf("Connect failed: %s\n", mysqli_connect_error() );
	exit();
}

/*
$result = query_first("INSERT INTO `account` (`account_username`,`account_password`,`account_email`,`account_active`) VALUES ('bbemis','password','bbemis@purdue.edu','0');");
echo $result;
*/

//$result = query_first("SELECT * FROM `account`;");


/*
 * Insert returns true on success
 * Select returns first record as assocative array
 */
function query_first($query){
	global $db_link;
	$data = array();

	$result = mysqli_query($db_link,$query);

	if( $result === TRUE ){
		return true;
	}
	else if( $result === FALSE ){
		return false;
	}
	else if($result){
		$data = $result->fetch_assoc();	
	}
	
	mysqli_free_result($result);
	return $data;
}

function escape_str($str){
	global $db_link;
	return $db_link->real_escape_string($str);
}


function db_close(){
	global $db_link;
	mysqli_close($db_link);
}

?>
