<?php

global $globals, $main;

error_reporting(0);

// Include class file 
include_once('class.php');

// Create the object of class
$main = new Main();

//Include header
include_once('header.php');

//Include footer
include_once('footer.php');

// Now we have to include our acts file 
$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

// Now check if any Api call
$globals['api'] = isset($_REQUEST['api']) ? $_REQUEST['api'] : '';

// Call the page and function for the same act
if(!empty($action)){
	$main->run_function($action);
}else{
	$main->run_function('show');
}

?>