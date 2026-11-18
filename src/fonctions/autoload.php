<?php
spl_autoload_register(function ($name){
    include './classes/'.$name.'.class.php';
})
?>
