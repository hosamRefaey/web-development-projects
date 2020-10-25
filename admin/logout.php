<?php

session_start(); //start the session

session_unset(); //unset session values

session_destroy(); //destroy the session

header('location:index.php');
exit();    
?>