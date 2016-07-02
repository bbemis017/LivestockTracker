<?php

require $MODELS . $DATABASE;

function query_first($query){

  $result = query($query);
  if( !isset($result) ){
    return false;
  }

  else if( $result === TRUE ){
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
