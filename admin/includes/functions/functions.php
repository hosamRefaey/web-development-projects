<?php
//A full function to select any fields you want from any table with any condition 
function getallfrom($field,$table,$where=null,$and=null,$ordering,$order_method='DESC'){
    global $connection;
    $query="SELECT $field FROM $table $where $and ORDER BY $ordering $order_method";
    $execute_query=mysqli_query($connection,$query);
    $items=mysqli_fetch_all($execute_query);
    return $items;
}

//function to get categories
function getcat(){
    global $connection;
    $query="SELECT * from categories ORDER BY ID DESC";
    $execute_query=mysqli_query($connection,$query);
    $rows=mysqli_fetch_all($execute_query);
    return $rows;
}

function gettitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }
    else{
        echo "default";
    }
}
function redirect($theMsg,$url,$seconds=3){
    if($url==null){
        $url='index.php';
    }
    
    elseif(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){ $url=$_SERVER['HTTP_REFERER']; }
    
    echo "<div class='container'>";
    echo $theMsg;
    echo "<div class='alert alert-info'>"."You Will Be Redirected To $url After $seconds Seconds"."</div>";
    echo "</div>";
    header("refresh:$seconds;url=$url");
    exit();
}
//function to check if value is already exits in database or not
//check item v1.0
function checkitem($select,$table,$value){
    global $connection;
  $checkQuery="SELECT $select FROM $table WHERE $select='$value'";
    $executeCheckQuery=mysqli_query($connection,$checkQuery);
    $rows=mysqli_fetch_all($executeCheckQuery);
    return count($rows);
}

function checkitem1($select,$table,$value,$id){
    global $connection;
  $checkQuery="SELECT $select FROM $table WHERE $select='$value' AND ID='$id'";
    $executeCheckQuery=mysqli_query($connection,$checkQuery);
    $rows=mysqli_fetch_all($executeCheckQuery);
    return count($rows);
}
function countElements1($select,$table){
    global $connection;
     $query="SELECT COUNT($select) FROM $table ";
    $execute_query=mysqli_query($connection,$query);
    $rows=mysqli_fetch_array($execute_query);
    return $rows[0];
}

//function to count elements in a table v2.0
function countElements($select,$table,$condition,$condValue){
    global $connection;
     $query="SELECT COUNT($select) FROM $table WHERE $condition=$condValue";
    $execute_query=mysqli_query($connection,$query);
    $rows=mysqli_fetch_array($execute_query);
    return $rows[0];
}

//function to get latest items
function getLatest($select,$table,$order,$limit){
    global $connection;
    $query="SELECT $select from $table ORDER by $order DESC limit $limit
";
    $execute_query=mysqli_query($connection,$query);
    $rows=mysqli_fetch_all($execute_query);
    return $rows;
}
?>