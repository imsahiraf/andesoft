<?php

// Include class file 
include_once('class.php');

// Create the object of class
$main = new Main();

//Include header
include_once('header.php');

// Now we have to include our acts file 
$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

// Call the page and function for the same act
if(!empty($action)){
	$main->run_function($action);
}else{
	$mail->run_function('index');
}

//Include footer
include_once('footer.php');

?>