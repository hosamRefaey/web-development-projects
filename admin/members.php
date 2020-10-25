
<?php
/*
===========================================
== manage members page
== add/delete/edit members from here
===========================================
*/
ob_start();
session_start();

$pageTitle='members';
if(isset($_SESSION['username'])){
include "init.php";

$userId=$_SESSION['userId'];
if (isset($_GET['do'])){
    $do=$_GET['do'];
}
else{
    $do='manage';
}
if($do=='manage'){
    if(isset($_GET['page']) && $_GET['page']=='pending'){
        $addQuery="AND regStatus=0";
    }
    $query="SELECT * FROM users WHERE groupId!=1 $addQuery ORDER By userId DESC";
    $execute_query=mysqli_query($connection,$query);
    $countMembers=countElements('userId','users','groupId',0);
    if($countMembers>0){

?>
    
    <h1 class="text-center">Manage Members</h1> 
    <div class="container table-responsive">
        <table class="text-center table table-bordered">
            <thead>
                <td>#ID</td>
                <td>Image</td>
                <td>Username</td>
                <td>EMAIL</td>
                <td>Full Name</td>
                <td>Registered Date</td>
                <td>Control</td>
            </thead>
<div class='popup imagePop'>
                                <div class='inner'><img width='300px'  hight='300px' src='' ><button class='btn btn-danger'><i class='fas fa-window-close'></i></button></div>  
                            </div>
                <?php
                  while($rows=mysqli_fetch_array($execute_query)) {
                      $avatar=$rows[Avatar];
                      
                      echo "<tr>";
                      echo "<td>$rows[userId]</td>";
                      if (!empty($avatar)){echo "<td><img data-popup='imagePop' class='clickToPop' src='uploads/avatars//$avatar' width='100px' height='100px'></td>";}
                      else{echo "<td>No Image</td>"; }
                      
                      echo "<td>$rows[userName]</td>";
                      echo "<td>$rows[Email]</td>";
                      echo "<td>$rows[fullName]</td>";
                      echo "<td>$rows[Date]</td>";
                      echo "<td>
                      <a href='members.php?do=edit&&userId=$rows[userId] ' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
                      <a href='members.php?do=delete&&userId=$rows[userId]' class='btn btn-danger confirm'><i class='fas fa-window-close'></i>Delete</a>";
                      if($rows[regStatus]==0){
                          echo"<a href='members.php?do=activate&userId=$rows[userId]' class='btn btn-info '><i class='fas fa-user'></i>Activate</a>
                      </td>";}
                      echo "</tr>";

                } 
        ?>

        </table>
        <a href="members.php?do=add" class="btn btn-primary"><i class="fas fa-plus"></i> New Member</a>
        
    </div>
    <?php }
    else{
        echo "<div class='container'>";
        echo "<div class='alert alert-info'>There Is No Members To Show</div>";
        echo "<a href='members.php?do=add' class='btn btn-primary'><i class='fas fa-plus'></i> New Member</a>";    
        echo "</div>";
        }
}

//Edit Page
elseif($do=='edit'){
    $userid=isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']):0;
    $query="SELECT * FROM users WHERE userId=$userid LIMIT 1";
    $execute_query=mysqli_query($connection,$query);
    $rows=mysqli_fetch_array($execute_query);
    
    if(count($rows)>0){?>
       <h1 class="text-center text-lg">Edit Members</h1>
  <div class="container">
  <form class="form-horizontal" action="?do=update" method="post" enctype="multipart/form-data">
      <input type="hidden" name="userid" value="<?php echo $rows['userId'] ?>">
      <div class=" form-group form-group-lg row">
          <label class="col-md-2 control-label">UserName</label>
          <input type="text" value="<?php echo $rows['userName']?>"  class="col-md-10  form-control" autocomplete="off" name="username" required="required">
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-md-2 control-label">Password</label>
          <input type="hidden" name="oldpassword" value="<?php $rows['password']?>">
          <input type="password"  class="password col-md-10  form-control" autocomplete="new-password" name="newpassword" >
          <i class="eye fas fa-eye fa-2x"></i>
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-md-2 control-label">Email</label>
          <input type="text" value="<?php echo $rows['Email']?>" class="col-md-10  form-control" autocomplete="off" name="email" required="required">
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-md-2 control-label">Full Name</label>
          <input type="text" value="<?php echo $rows['fullName']?>" class="col-md-10  form-control" autocomplete="off" name="fullname" required="required">
      </div>
      <div class="row form-group">
          <label class="col-md-2 col-form-label" for="avatar">Image</label>
          <input type="file" class="form-control col-md-10" placeholder="Upload Your Image" name="avatar" id="avatar">
      </div>
      <div calss="form-group form-group-lg">

          <input type="submit" name='save'  value="Save" class="offset-md-2  btn btn-lg btn-primary">

          </div>
</form><?php
       $query="SELECT comments.*  ,items.Name FROM comments INNER JOIN items ON comments.Item_ID=items.item_ID WHERE User_ID=$rows[userId]";
        $execute_query=mysqli_query($connection,$query);
        $countComments=countElements('Comment','comments','User_ID',$rows[userId]);
        if($countComments>0){               
        echo "<h1 class='text-center'>Manage [$rows[userName]] Comments</h1>";?>
              
            <table class="table text-center table-bordered">
                <thead>
                    
                    <td>Comment</td>
                    <td>Item Name</td>
                 
                    <td>Added Date</td>
                    <td>Control</td>
                </thead>
                
                    <?php
                        while($comments=mysqli_fetch_array($execute_query)){
                            echo "<tr>
                                  <td>$comments[Comment]</td>
                                  <td>$comments[Name]</td>
                                  
                                  <td>$comments[Date]</td>
                                  <td><a href='comments.php?do=edit&id=$comments[Comment_ID]' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
                                  <a href='comments.php?do=delete&id=$comments[Comment_ID]' class='confirm btn btn-danger'><i class='fas fa-window-close'></i>Delete</a>";
                                  if($comments[Status]==0){
                                  echo "<a href='comments.php?do=approve&id=$comments[Comment_ID]' class='btn btn-info'><i class='fas fa-check'></i>Approve</a>";};
                                  echo "</td></tr>" ;
                        }
            }
                    ?>    
                
            </table>
      
        </div>
      
<?php   }
    else{
        $theMsg= "<div class='alert alert-danger'>there is no account with this userid</div>";
        redirect($theMsg,'');
    }



 }

//update page    
elseif($do=='update'){

    if($_SERVER['REQUEST_METHOD']=='POST'){
        echo "<h1 class='text-center text-lg'>Update Members</h1>";
            $id=$_POST['userid'];
            $usename=$_POST['username'];
            $mail=$_POST['email'];
            $fulname=$_POST['fullname'];
            $avatar=$_FILES['avatar'];
            //avatar variabes
            $avatarName=$avatar['name'];
            $avatarName=rand(0,200000)."_".$avatarName;
            $avatarSize=$avatar['size'];
           $avatarTmp=$avatar['tmp_name'];
          
            //allowed extintions
            $allowedtypes=array("jpeg","jpg","png","gif");
            $avatarExtintion=end(explode(".",$avatarName));
        echo $avatarName;
        //password trick
        $pass=empty($_POST['newpassword'])?$_POST['oldpassword']:md5($_POST['newpassword']);

        //inputs validation
        $errors=array();
        if(empty($usename)){$errors['username']='username can not be <strong>empty</strong>';}
        else if(strlen($usename)<4 || strlen($usename)>20){
            $errors['username']='username must not be <strong>less than 4 charcters or more than 20 characters</strong>';
        }
        if(empty($mail)){
            $errors['mail']='email can not be <strong>empty</strong>';
        }
        else if(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $errors['mail']='Please enter your email <strong>correctly</strong>';
        }
        if(empty($fulname)){
            $errors['fullname']='name can not be <strong>empty</strong>';
        }
        if (empty($avatarName)){
            $errors['avatar']='Image can not be <strong>empty</strong>';
        }
        elseif(!in_array($avatarExtintion,$allowedtypes)){
            $errors['avatar']='Image extinsion Is Not Allowed';
        }
        elseif ($avatarSize>3000000){
            $errors['avatar']='Image Size Is Too Large';
        } 
        
       
        if (empty($errors)){
            $checkQuery="SELECT COUNT(userName) FROM users WHERE userName='$usename' AND userId!='$id'";
            $executeCheckQuery=mysqli_query($connection,$checkQuery);
            $userCount=mysqli_fetch_array($executeCheckQuery);
            if($userCount[0]==0){
               //execute Update query
                $query="UPDATE users SET userName='$usename',password='$pass',Email='$mail',fullName='$fulname',Avatar='$avatarName' WHERE userId='$id'";
                if($execute_query=mysqli_query($connection,$query))
                {
                    $theMsg= "<div class='alert alert-success'>updated</div>";
                    move_uploaded_file($avatarTmp,"uploads\avatars\\".$avatarName);
                    redirect($theMsg,$_SERVER['HTTP_REFERER']);
                }
                else  $errors['database']= '<strong>Database </strong>Error';
            }
            elseif($userCount[0]==1){
                $theMsg= "<div class='alert alert-danger'>User Is Already Exist</div>";

                    redirect($theMsg,$_SERVER['HTTP_REFERER']);
            }
            
            }
       else{ 
           foreach($errors as $error){
            echo "<div class='container'>"."<div class='alert alert-danger'>".$error."</div>"."</div>";
            }   
        }

        }
    else{
        $errormsg= "<div class='alert alert-danger'>this page can not entered directly</div>";
        redirect($errormsg,'back');
    }
}
    
    
//add page    
else if($do=='add'){?>
     <h1 class="text-center text-lg">Add Members</h1>
  <div class="container">
  <form action="?do=insert" method="post" enctype="multipart/form-data">

      <div class=" form-group row form-group-lg">
          <label for="user" class="col-sm-2 col-form-label">UserName</label>
            
                <input type="text" id="user" class="col-sm-10 form-control" autocomplete="off" name="username" placeholder="Enter Your UserName" required="required">
           
      </div>
      <div class=" form-group row form-group-lg">
          <label for="pass" class="col-sm-2 col-form-label">Password</label>
          
              <input type="password" id="pass" placeholder="Enter Your Password" class="password col-sm-10 form-control" autocomplete="new-password" required="required" name="password" >
          <i  class="eye fas fa-eye fa-2x"></i>
          
      </div>
      <div class=" form-group row form-group-lg">
          <label for="email" class="col-sm-2 col-form-label">Email</label>
         
            <input type="text" id="email" class="col-sm-10 form-control" autocomplete="off" placeholder="Enter Your Email" name="email" required="required">
           
      </div>
      <div class=" form-group row form-group-lg ">
          <label for="name" class="col-sm-2 col-form-label">Full Name</label>
          
          <input type="text" id="bame" class="col-sm-10 form-control" autocomplete="off" placeholder="Enter Your Full Name" name="fullname" required="required">
          
      </div>
      <div class="row form-group">
          <label class="col-md-2 col-form-label" for="avatar">Image</label>
          <input type="file" class="form-control col-md-10" placeholder="Upload Your Image" name="avatar" id="avatar">
      </div>
      <div calss="form-group row form-group-lg">
          <div class="row form-group">
          <input type="submit" name='Addmember'  value="Add Member" class="offset-md-2 btn btn-lg btn-primary">
          </div>
          </div>
</form>
      </div> 
<?php }
    
//insert page    
else if($do=='insert'){
      if($_SERVER['REQUEST_METHOD']=='POST'){
        echo "<h1 class='text-center text-lg'>Add Members</h1>";
            $usename=$_POST['username'];
            $pass=$_POST['password'];
            $hashedpass=md5($pass);
            $mail=$_POST['email'];
            $fulname=$_POST['fullname'];
            $avatar=$_FILES['avatar'];
            //avatar variabes
            $avatarName=$avatar['name'];
            $avatarName=rand(0,200000)."_".$avatarName;
            $avatarSize=$avatar['size'];
           $avatarTmp=$avatar['tmp_name'];
          
            //allowed extintions
            $allowedtypes=array("jpeg","jpg","png","gif");
            $avatarExtintion=end(explode(".",$avatarName));
            

        //inputs validation
        $errors=array();

        if(empty($usename)){$errors['username']='username can not be <strong>empty</strong>';}
        else if(strlen($usename)<4 || strlen($usename)>20){
            $errors['username']='username must not be <strong>less than 4 charcters or more than 20 characters</strong>';
        }
       //password trick
       $passfeild=empty($pass)? $errors['password']='Please Enter Your Password':$pass=$hashedpass;
        if(empty($mail)){
            $errors['mail']='email can not be <strong>empty</strong>';
        }
        else if(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $errors['mail']='Please enter your email <strong>correctly</strong>';
        }
        if(empty($fulname)){
            $errors['fullname']='name can not be <strong>empty</strong>';
        }
        if (empty($avatarName)){
            $errors['avatar']='Image can not be <strong>empty</strong>';
        }
        elseif(!in_array($avatarExtintion,$allowedtypes)){
            $errors['avatar']='Image extinsion Is Not Allowed';
        }
        elseif ($avatarSize>3000000){
            $errors['avatar']='Image Size Is Too Large';
        }  
            
          

        if (empty($errors)){
            
           $checkUser=checkitem('userName','users',$usename);

              if($checkUser>0)
              {
                 $theMsg= "<div class='alert alert-danger'>user is already exist</div>";
                 redirect($theMsg,'');
                }   
                else{   

           //execute database query
                   $query="INSERT INTO users(userName,password,Email,fullName,regStatus,Date,Avatar) VALUES('$usename','$hashedpass','$mail','$fulname',1,now(),'$avatarName')";
                    if($execute_query=mysqli_query($connection,$query)){
                        $theMsg= "<div class='alert alert-success'>Record added</div>";
                        move_uploaded_file($avatarTmp,"uploads\avatars\\".$avatarName);
                        redirect($theMsg,'back');
                        
                    }
                    
                   else $errors['database']= 'Database Error';
            }
        }
       else{ 
           foreach($errors as $error){
            echo "<div class='container'>"."<div class='alert alert-danger'>".$error."</div>"."</div>";
        }
        }

        }
    else{
       $theMsg="<div class='alert alert-danger'>this page can not entered directly</div>";
        redirect($theMsg,'');
    }
}
  
    
//Delete page   
elseif($do=='delete'){
    echo '<h1 class="text-center text-lg">Delete Members</h1>
  <div class="container">';
    $userid=isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']):0;
    $check=checkitem('userId','users',$userid);
    if($check>0){
       $delquery="DELETE FROM users WHERE userId=$userid";
       if($execute_delquery=mysqli_query($connection,$delquery)){
           $theMsg= "<div class='alert alert-success '>user deleted</div>";
           redirect($theMsg,'back',1);
       } 
        else{
            echo "<div class='container'><strong class='alert alert-danger'>Database Error</strong></div>";
        }
        echo '</div>';
    }
}

    //Activate Page
elseif ($do=='activate'){
    echo '<h1 class="text-center text-lg">Activate Members</h1>
  <div class="container">';
    $userid=isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']):0;
     $check=checkitem('userId','users',$userid);
    if($check>0){
       $delquery="UPDATE users SET regStatus=1 WHERE userId=$userid";
       if($execute_delquery=mysqli_query($connection,$delquery)){
           $theMsg= "<div class='alert alert-success '>user Activated</div>";
           redirect($theMsg,'back');
       } 
        else{
            echo "<div class='container'><strong class='alert alert-danger'>Database Error</strong></div>";
        }
        echo '</div>';
    }
}    
include $templates."footer.php";
}
else{ 
header('location:index.php');
}
ob_end_flush();
?>