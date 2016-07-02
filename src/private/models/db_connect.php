<?php

require $MODELS . $DATABASE;

function query_first($query){
  $data = array();

  $result = query($query);
  echo "</br>" . $result . "</br>";

  if( $result === TRUE ){
    return true;
  }
  else if( $result === FALSE ){
    return false;
  }
  else if( $result){
    $data = assoc($result);
  }

  free_result($result);
  return $data;
}

?>
