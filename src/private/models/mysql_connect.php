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
//

function query($query){
	global $db_link;
  return mysqli_query($db_link,$query);
}

function free_result($result){
  mysqli_free_result($result);
}


function assoc($result){
  return $result->fetch_assoc();
}

/*
 * Insert returns true on success
 * Select returns first record as assocative array
 */
/*
function query_first($query){
	global $db_link;
	$data = array();

	$result = query($query);

	if( $result === TRUE ){
		return true;
	}
	else if( $result === FALSE ){
		return false;
	}
	else if($result){
		$data = $result->fetch_assoc();
	}

	free_result($result);
	return $data;
}
*/

function query_array($query){

	$data = array();

	$result = query($query);

	if( $result === FALSE){
		return false;
	}

	while( $row = mysqli_fetch_assoc($result) ){
		array_push( $data, $row);
	}

	free_result($result);

	return $data;
}

/**
 * sends select query to db, then returns assocative array with one column as the
 * hash and the other as the value
 * @param query String - sql query to send to db
 * @param hashColumn String - table column to use as the hash of the array
 * @param valueColumn String - table column to use as the value in the array
 * @return associative array
 */
function query_associative( $query, $hashColumn, $valueColumn){
	$data = array();

	$result = query($query);
	if( $result === FALSE){
		return false;
	}

	while( $row = mysqli_fetch_assoc($result) ){
		$data[ $row[$hashColumn] ] = $row[ $valueColumn ];
	}

	free_result($result);
	return $data;
}

function escape_str($str){
	global $db_link;
	return $db_link->real_escape_string($str);
}


function db_close(){
	global $db_link;
	if(is_resource($db_link) && get_resource_type($db_link) === 'mysql link'){
		mysqli_close($db_link);
	}
}

?>
