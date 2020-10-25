<?php
ob_start();
session_start();
$pageTitle='Comments';
if(isset($_SESSION['username'])){
    include 'init.php';
    $do=isset($_GET['do'])?$_GET['do']:'manage';
    if($do=='manage'){
        $query="SELECT comments.* ,users.userName ,items.Name FROM comments INNER JOIN users ON comments.User_ID=users.userId INNER JOIN items ON comments.Item_ID=items.item_ID ORDER By Comment_ID DESC";
        $execute_query=mysqli_query($connection,$query);
        $countCommnets=countElements1('Comment_ID','comments');
        if($countCommnets>0){
        echo "<h1 class='text-center'>Manage Comments</h1>";?>
        <div class="container">        
            <table class="table text-center table-bordered">
                <thead>
                    <td>ID</td>
                    <td>Comment</td>
                    <td>Item Name</td>
                    <td>User Name</td>
                    <td>Added Date</td>
                    <td>Control</td>
                </thead>
                
                    <?php
                        while($rows=mysqli_fetch_array($execute_query)){
                            echo "<tr><td>$rows[Comment_ID]</td>
                                  <td>$rows[Comment]</td>
                                  <td>$rows[Name]</td>
                                  <td>$rows[userName]</td>
                                  <td>$rows[Date]</td>
                                  <td><a href='?do=edit&id=$rows[Comment_ID]' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
                                  <a href='?do=delete&id=$rows[Comment_ID]' class='confirm btn btn-danger'><i class='fas fa-window-close'></i>Delete</a>";
                                  if($rows[Status]==0){
                                  echo "<a href='?do=approve&id=$rows[Comment_ID]' class='btn btn-info'><i class='fas fa-check'></i>Approve</a>";};
                                  echo "</td></tr>" ;
                        }
                    ?>    
                
            </table>
        </div>
    <?php 
        }
        else{
        echo "<div class='container'>";
        echo "<div class='alert alert-info'>There Is No Comments To Show</div>";   
        echo "</div>";
        }
    }
    
/*Start of add page*/    
/*End of add page*/
    
/*Start of edit page*/
    elseif($do=='edit'){
        echo "<h1 class='text-center'>Edit Comment</h1>";
        $commId=isset($_GET['id'])?$_GET['id']:0;
        $checkComm=checkitem('Comment_ID','comments',$commId);
        if($checkComm>0){
            $query="SELECT * FROM comments WHERE Comment_ID=$commId";
            $execute_query=mysqli_query($connection,$query);
            $row=mysqli_fetch_array($execute_query); ?>
            <div class="container">
              <form action="comments.php?do=update" method="post">
                  <div class="row form-group">
                      <label class="col-md-2 col-form-label" for="comment">Comment</label>
                      <input type="hidden" value="<?php echo $commId?>" name="c_id">
                      <textarea id="comment" class="form-control  col-md-10" name="comment"><?php echo "$row[Comment]";?></textarea>
                  </div>

                  <div class="row">
                      <div class="offset-md-2">
                          <input class="btn btn-primary" type="submit" value="Save">
                      </div>
                  </div>
              </form>
            </div>    
        <?php }
    }
/*End of edit page*/
    
/*Start of Update page*/
    elseif($do=='update'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo "<h1 class='text-center'>Update Page</h1>";
            $comment=$_POST['comment'];
            $commid=$_POST['c_id'];
            if(empty($comment)){
                $erMsg="<div class='alert alert-danger'>Comment Can Not Be Empty</div>";
                redirect($erMsg,$_SERVER['HTTP_REFERER']);
            }
            else{
                $query="UPDATE comments SET Comment='$comment' WHERE Comment_ID=$commid";
                if ($execute_query=mysqli_query($connection,$query)){
                    $theMsg="<div class='alert alert-success'>Comment Updated</div>";
                    redirect($theMsg,'');
                }
                else{
                    $dberMsg="<div class='alert alert-danger'>Database Error</div>";
                    redirect($dberMsg,$_SERVER['HTTP_REFERER']);
                }
            }
        }
        else{
            $erMsg="<div class='alert alert-danger'>You Can Not Browse This Page Directly</div>";
            redirect($erMsg,'');
        }
    }
/*End of update page */
    
/*Start of Delete page */
    if($do=='delete'){
        $commid=isset($_GET['id'])&&is_numeric($_GET['id'])?intval($_GET['id']):0;
        $checkCommId=checkitem('Comment_ID','comments',$commid);
        if($checkCommId>0){
            $query="DELETE FROM comments WHERE Comment_ID=$commid";
            if ($execute_query=mysqli_query($connection,$query)){
                    $theMsg="<div class='alert alert-success'>Comment Deleted</div>";
                    redirect($theMsg,$_SERVER['HTTP_REFERER']);
                }
                else{
                    $dberMsg="<div class='alert alert-danger'>Database Error</div>";
                    redirect($dberMsg,$_SERVER['HTTP_REFERER']);
                }
        }
    }
/*End of Delete page */
    
/*Start of Approve page */    
    elseif ($do=='approve'){
    echo '<h1 class="text-center text-lg">Approve Comments</h1>
  <div class="container">';
    $commid=isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0;
     $check=checkitem('Comment_ID','comments',$commid);
    if($check>0){
       $delquery="UPDATE comments SET Status=1 WHERE Comment_ID=$commid";
       if($execute_delquery=mysqli_query($connection,$delquery)){
           $theMsg= "<div class='alert alert-success '>Comment Approved</div>";
           redirect($theMsg,'');
       } 
        else{
            echo "<div class='container'><strong class='alert alert-danger'>Database Error</strong></div>";
        }
        echo '</div>';
    }
}
/*End of Approve page */    
    include $templates."footer.php";
}
else{ 
header('location:index.php');
}
ob_end_flush();

?>