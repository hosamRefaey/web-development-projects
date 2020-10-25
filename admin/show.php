<?php
    include "db.php";
    $query="SELECT * FROM `users`";
    $execute_query=mysqli_query($connection,$query);
    echo "<table>";
    while ($rows=mysqli_fetch_array($execute_query)) {
        echo "<tr><td>".$rows['userId']."</td>";
        echo "<td>".$rows['userName']."</td>";
        echo "<td>".$rows['fullName']."</td><td><button onclick='del(".$rows['userId'].");'>X</button></td></tr>";
    }
    echo "</table>";
?>
