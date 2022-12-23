<?php

// Have a list of files to extend classes and traits
$flists = ['db', 'table', 'procedures', 'traits'];

// Include all extended classes files
foreach ($flists as $li){
    include_once($li.'.php');
}

class Main{

    protected $con;

    function __construct(){
        
    }
}

?>