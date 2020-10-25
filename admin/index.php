<?php
session_start();
$nonavbar='';
include "init.php";
$pageTitle='LogIn';
if(isset($_SESSION['password'])){
   header('location:controlPanel.php');
     
}

//get elements from form
if($_POST[login]){
    $userName=$_POST['uname'];
    $password=$_POST['password'];
    $password=md5($password); 
}
//select admins from database
$query="SELECT userId,userName,password FROM users WHERE userName='$userName' AND password='$password' AND groupID='1' ";
$execute_query=mysqli_query($connection,$query);
$rows=mysqli_fetch_array($execute_query);
if(count($rows)>0){
    header('location:controlPanel.php');
    $_SESSION['username']=$userName;
    $_SESSION['password']=$password;
    $_SESSION['userId']=$rows['userId'];
   
}

?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <h4 class="text-center"> Admin Log in Form </h4>
    <input class="form-control" name="uname" placeholder="UserName" type="text" autocomplete="off">
    <input class="form-control" name="password" placeholder="Password" type="password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" name="login" value="LogIn" >
</form>
<?php
include "includes/templates/footer.php";
?>