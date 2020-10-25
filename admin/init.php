<?php

//routes
$templates='includes\templates\\'; //control panel templates directory
$english='includes\langs\english.php'; //english language directory
$navbar='includes\templates\\'; //navbar code
$language='includes\langs\\'; //languages directory
$functions='includes\functions\\'; //functions directory
$css='layout\css\\'; //control panel stylesheets route
$js='layout\js\\'; //control panel jquery , bootstrab , and admin codes

//includes 
include "db.php"; //database connection 

include $language.'english.php';
include $functions.'functions.php';
include $templates.'header.php';
if(!isset($nonavbar)){
include $navbar.'navbar.php';
}
?>