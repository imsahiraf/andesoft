<?php
function __req($name){
    global $globals;
    $globals['var'][$name] = $_REQUEST[$name];
    return $_REQUEST[$name];
}
?>