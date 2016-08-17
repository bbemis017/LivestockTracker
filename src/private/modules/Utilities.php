<?php

function active_module($name){
	global $MODULES;
	return array_key_exists($name, $MODULES) && $MODULES[$name];
}
?>
