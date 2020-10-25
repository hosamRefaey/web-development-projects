<?php
    include "db.php";
    $query="INSERT INTO `users`(`userName`) VALUES ('$_POST[userName]')";
    $execute_query=mysqli_query($connection,$query);
?>