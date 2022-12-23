<?php

// Extended class DB
class DB extends Main{

    protected $con;

    function __contruct() {

        $servername = "localhost";
		$username = "root";
		$password = "";

        try{
			$this->con = mysqli_connect($servername, $username, $password);
		}catch(exception $e){
			die("Connection failed: " . $e->getMessage());
		}

        $this->connectDB();
    }

    function connectDB(){

        $db = 'andesoft';
		$queryDB = 'CREATE DATABASE IF NOT EXISTS '.$db;
		mysqli_query($this->con, $queryDB);
		mysqli_select_db($this->con, $db) or die(mysql_error("database")); 

    }
}
?>