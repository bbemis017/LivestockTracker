<?php
class Module {
	var $name;
	var $active;

	public function __construct($name,$active){
		$this->$name;
		$this->$active;
	}

	public static function getModules(){
		$sql = "SELECT `module_name`,`module_active` FROM `module`;";
		$result = query_associative($sql,"module_name","module_active");
		return $result;
	}
}
?>
