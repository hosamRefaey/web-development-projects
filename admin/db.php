<?php
$host="localhost";
$userName="root";
$password="yourNewPassword";
$dbName="shop";
$connection=mysqli_connect($host,$userName,$password,$dbName);
if(mysqli_connect_error($connection)){
    echo "connection error : ".mysqli_connect_error();
}
  
?>