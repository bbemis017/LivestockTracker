<?php

class sqliteDB extends SQLite3 {

  function __construct(){
    $this->open($MODELS.'sqlite.db');
    if(error){
      echo "error sqlite open";
    }
  }
}

$db = new sqliteDB();
if(!$db){
  echo $db->lastErrorMsg();
}
else {
  echo "Opened databse successfully\n";
}

function query($query){
  global $db;
  return $db->query($query);
}

function free_result($result){
  $result->free();
}

function assoc($result){
  return $result->fetchArray(SQLITE3_ASSOC);
}

function db_close(){
  global $db;
  $db->close();
}

function escape_str($str){
  global $db;
  return $db->escapeString($str);
}

?>
