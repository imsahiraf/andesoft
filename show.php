<?php

class show extends Main {

    public static function show(){
         
    }

    public static function show_theme(){
        __header();
        echo '';
        __footer();
    }

    public static function show_api(){
        $a = ['a', 'b'];
        return $a;
    }
}

?>