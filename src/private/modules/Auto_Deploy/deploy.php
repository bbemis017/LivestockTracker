<?php
print_r($MODULES);
if( active_module("Auto_Deploy")){
	echo "true";
}
else{
	echo "false";
}
?>
