<?php
session_start();
$nonavbar='';
$pageTitle='DashBoard';
include "init.php";
if(isset($_SESSION['username'])){
    
    include $navbar."navbar.php";
    ?>
    <div class="fMenu">
        <i class="fas fa-cog"></i>
    </div>
    <div class="container text-center">
        <h1 class="text-center">Dashboard</h1>
        <div class="row ">
            <div class="col-sm-6  col-lg-3 ">
                <div class="stats boxEffect st-members">
                    <i class="fas fa-users"></i>
                    <div class="float-right">
                        Total Members<span class="number"><a href="members.php"><?php echo countElements('userId','users','groupId',0) ?></a></span>
                    </div>
                    <div class="effect"></div>
               </div>
                
            </div>
             <div class="col-sm-6 col-lg-3 ">
                 <div class="stats st-pending">
                 <i class="fas fa-user-plus"></i>
                    <div class="float-right">
                        Pendig Members<span class="number"><a href="members.php?do=manage&page=pending"><?php echo checkitem('regStatus','users','0')?></a></span>
                    </div>
                 </div>
            </div>
             <div class="col-sm-6 col-lg-3 ">
                 <div class="stats st-items">
                    <i class="fas fa-user-tag"></i>
                    <div class="float-right">
                        Total Items<span class="number"><a href="items.php"><?php echo countElements1('item_ID','items') ?></a></span>
                    </div>
                 </div>
            </div>
             <div class="col-sm-6 col-lg-3 ">
                 <div class="stats st-comments">
                    <i class="fas fa-comments"></i>
                    <div class="float-right">
                        Total Comments<span class="number"><a href="comments.php"><?php echo countElements1('Comment_ID','comments') ?></a></span>
                    </div>
                 </div>
            </div>
         
        </div>    
    </div>  
    <div class="container latest">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-users"></i>
                            Latest Registered users
                            <span class="laSelect float-right">
                            <i class="fas fa-minus "></i>
                            </span>    
                        </div>
                        <div class="card-body">         
                            <?php 
                                $latestUsers=getLatest('*','users','userId',4);
                               echo "<ul class='list-group lusers'>";
                                foreach($latestUsers as $user){
                                    echo "<li class='list-group-item'>"
                                        ."$user[1]"."<a href='members.php?do=edit&userId=$user[0]'><span class='btn btn-success luserbtn'><i class='fas fa-edit'></i>Edit</span></a>";
                                        if($user[7]==0){
                                            echo"<a href='members.php?do=activate&userId=$user[0]' class='btn btn-info luseractiv '><i  class='fas fa-user'></i>Activate</a>";};
                                       echo "</li>";
                                }    
                                echo "</ul>";                
                            ?>
                        </div>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-comment"></i>
                                Latest Comments
                                <span class="laSelect float-right">
                                <i class="fas fa-minus"></i>
                                </span>    
                            </div>
                            <div class="card-body">
                                <ul class="list-group lusers lcomments">
                                    <?php
                                        $query="SELECT comments.* , users.UserName FROM comments INNER JOIN users ON comments.User_ID=users.userId";
                                        $execute_query=mysqli_query($connection,$query);
                                        while($rows=mysqli_fetch_array($execute_query)){
                                            echo "<li class='list-group-item'>
                                            <span class='memberN'><a href='members.php?do=edit&userId=$rows[User_ID]'>$rows[UserName]</a></span>
                                            <p class='memberC'>$rows[Comment]</p>
                                            </li>";
                                        }
                                    ?>
                                </ul>    
                            </div>
                        </div>
                    </div>
            </div>
        </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-tag"></i>
                        Latest Items
                        <span class="laSelect float-right">
                        <i class="fas fa-minus "></i>
                        </span>    
                    </div>
                    <div class="card-body"> 
                        <ul class="list-group lusers">
                        <?php
                            $latestitems=getLatest('*','items','item_ID',6);
                            foreach($latestitems as $item){
                                echo "<li class='list-group-item'>$item[1]
                                <span class='float-right'>
                                <a href='items.php?do=edit&id=$item[0]' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>";
                                if($item[9]==0){    
                               echo " <a href='items.php?do=approve&id=$item[0]' class='btn btn-info'><i class='fas fa-check'></i>Approve</a>";}
                                echo "</span>
                                </li>";
                            }
                        ?>
                            
                        </ul>
                    </div>
                </div>
            </div>
      
        
              </div>
    </div>
    <?php
   
    include $templates."footer.php";
}
else{ 
    header('location:index.php');
    }

?>