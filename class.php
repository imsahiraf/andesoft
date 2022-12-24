<?php

// Have a list of files to extend classes and traits
$flists = ['db', 'table', 'procedures', 'traits', 'helper'];

// Include all extended classes files
foreach ($flists as $li){
	include_once($li.'.php');
}

class Main{

	use DB;
	use DBTable;
	use Procedure;

	protected $con;

	function __construct(){

		$this->makeCon();
		$this->makeTables();
		$this->makeProcedures();
		
	}

	function executeQuery($sql){
		return mysqli_query($this->con, $sql);
	}

	function run_function($func){

		global $globals;

		include_once($func.'.php');
		$func::$func();
		if(!empty($globals['api'])){
			$api_func = $func.'_api';
			$ret = $func::$api_func();
			echo json_encode($ret);
		}else{
			$theme_func = $func.'_theme';
			$func::$theme_func();
		}
		
	}

}
// "CALL insertuser('$name','$email','$contact','$addrss')"
?>