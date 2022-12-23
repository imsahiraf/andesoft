<?php

// Have a list of files to extend classes and traits
$flists = ['db', 'table', 'procedures', 'traits'];

// Include all extended classes files
foreach ($flists as $li){
	include_once($li.'.php');
}

class Main{

	use DB;
	use Table;

	protected $con;

	function __construct(){

		$this->makeCon();
		$this->makeTables();
		
	}

	function executeQuery($sql){
		return mysqli_query($this->con, $sql);
	}

	function run_function($func){

		include_once($func.'.php');
		$func::$func();
		$func::$func.'_api'();
		$func::$func.'_theme'();

	}

}

?>