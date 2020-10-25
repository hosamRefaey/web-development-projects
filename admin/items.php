<?php
ob_start();
session_start();
$pageTitle='items';
if(isset($_SESSION['username'])){
include "init.php";
   $do=isset($_GET['do'])?$_GET['do']:'manage';
    if($do=='manage'){
        $query="SELECT items.*,categories.Name AS Category_Name ,users.userName FROM items INNER JOIN categories on items.Cat_ID=categories.ID INNER JOIN users on items.Member_ID=users.userId ORDER BY item_ID DESC";
        $execute_query=mysqli_query($connection,$query);
        $countItems=countElements1('item_ID','items');
        if($countItems>0){
?>
        <h1 class="text-center">Manage Page</h1>
        <div class="container">
            <table class="table text-center table-bordered">
                <thead>
                    <td>#ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Adding Date</td>
                    <td>Username</td>
                    <td>Control</td>
                </thead>
                <?php 
                    while($rows=mysqli_fetch_array($execute_query))
                        {
                            echo "<tr><td>$rows[item_ID]</td>
                                  <td>$rows[Name]</td>
                                  <td>$rows[Description]</td>
                                  <td>$$rows[Price]</td>
                                  <td>$rows[Add_Date]</td>
                                  <td>$rows[Category_Name]</td>
                                  <td>$rows[userName]</td>
                                  <td><a href='?do=edit&id=$rows[item_ID]' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
                                  <a href='?do=delete&id=$rows[item_ID]' class='confirm btn btn-danger'><i class='fas fa-window-close'></i>Delete</a>";
                                  if($rows[Approve]==0){
                                  echo "<a href='?do=approve&id=$rows[item_ID]' class='btn btn-info'><i class='fas fa-check'></i>Approve</a>";};
                                  echo "</td></tr>" ;
                        }
                ?>
            </table>
                <a href="?do=add" class=" btn btn-primary" name="additem">Add Item</a>
            
        </div>    
    <?php }
        
        else{
        echo "<div class='container'>";
        echo "<div class='alert alert-info'>There Is No Items To Show</div>";  
        echo "<a href='items.php?do=add' class='btn btn-primary'><i class='fas fa-plus'></i> New Item</a>";     
        echo "</div>";
        }
    }
    if($do=='add'){?>
        <h1 class="text-center">Add Items</h1>
        <div class="container items">
            <form action="?do=insert" method="post">
                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Name</label>
                    <input type="text" class="form-control col-sm-10"  id="name" placeholder="Enter Category Name" name="name"><span class="asterisk">*</span>
                </div>
                <div class="form-group row">
                    <label for="describe" class="col-form-label col-sm-2">Description</label>
                    <input type="text" class="form-control col-sm-10" id="describe" placeholder="Enter Category Description" name="description"><span class="asterisk">*</span>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-form-label col-sm-2">Price</label>
                    <input type="text" class="form-control col-sm-10" id="price" placeholder="Enter Category Price" name="price"><span class="asterisk">*</span>
                </div>
                <div class="form-group row">
                    <label for="country" class="col-form-label col-sm-2">Country</label>
                    <input type="text" class="form-control col-sm-10" id="country" placeholder="Enter The countery Which Category Made in" name="country"><span class="asterisk">*</span>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-form-label col-sm-2">Status</label>
                    <div class="col-sm-10 select" id="status">
                    <select   name="status">
                    <option value="0">...</option>
                    <option value="1">New</option>
                    <option value="2">Like New</option>
                    <option value="3">Used</option>
                    <option value="4">Very Old</option>
                    </select>
                        </div>
                </div>
          
                <div class="form-group row">
                    <label for="member" class="col-form-label col-sm-2">Member</label>
                    <div class="col-sm-10 select" id="member">
                    <select   name="member">
                    <option value="0">...</option>
                    <?php
                        $users=getallfrom("*","users","","","userId","ASC");
                        foreach ($users as $user) {
                                echo "<option value='$user[0]'>$user[1]</option>";
                             }
                    ?> 
                    </select>
                        </div>
                </div>
                
                <div class="form-group row">
                    <label for="category" class="col-form-label col-sm-2">Category</label>
                    <div class="col-sm-10 select" id="category">
                    <select   name="category">
                    <option value="0">...</option>
                    <?php
                        $cats=getallfrom("*","categories","WHERE Parent=0","","ID","ASC");
                        foreach ($cats as $cat) {
                                echo "<option value='$cat[0]'>$cat[1]</option>";
                                $subcats=getallfrom("*","categories","WHERE Parent=$cat[0]","","ID","ASC");
                                foreach ($subcats as $subcat) {
                                  echo "<option value='$subcat[0]'>---$subcat[1]</option>";  
                                }
                             }
                    ?> 
                    </select>
                        </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 col-form-label" for="tags">Tags</label>
                    <input type="text" class="col-md-10 form-control" id="tags" placeholder="Seperate tags with , " name="tags">
                </div>
                <div class="form-group row">
                    <input type="submit" name="additem" value="Add Item" class="offset-md-2 btn btn-primary">
                </div>
            </form>
        </div>
    <?php }
    //insert page
    elseif($do=='insert'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $name=$_POST['name'];
            $description=$_POST['description'];
            $price=$_POST['price'];
            $country=$_POST['country'];
            $status=$_POST['status'];
            $member=$_POST['member'];
            $category=$_POST['category'];
            $tags=$_POST['tags'];
            
            if(empty($name)){
                $error['name']="Name Can not Be <strong>Empty</strong>";
            }
            if(empty($description)){
                $error['description']="description Can not Be <strong>Empty</strong>";
            }
            if(empty($price)){
                $error['price']="price Can not Be <strong>Empty</strong>";
            }
            if(empty($country)){
                $error['country']="country Can not Be <strong>Empty</strong>";
            }
            if($status==0){
                $error['status']="You Must Enter Item <strong>Status</strong>";
            } 
            if($member==0){
                $error['member']="You Must Enter The <strong>Member</strong>";
            }
            if($category==0){
                $error['category']="You Must Enter Item <strong>Category</strong>";
            }
            if(empty($error)){
                $query="INSERT INTO items(Name,Description,Price,Add_Date,Countery_Made,Status,Cat_ID,Member_ID,Tags) VALUES ('$name','$description','$price',now(),'$country','$status','$category','$member','$tags')";
                if(mysqli_query($connection,$query)){
                    $theMsg="<div class='alert alert-success'>One Item Inserted</div>";
                    redirect($theMsg,$_SERVER['HTTP_REFERER']);
                }
                else{
                   $erMsg="<div class='alert alert-danger'>DataBase Error</div>";
                    redirect($erMsg,'');
                }
            }
            else{
                foreach($error as $er){
                    echo "<div class='container'>";
                    echo "<div class='alert alert-danger'>$er</div>";
                    echo "</div >";
                }
            }
        }
        else{
            $theMsg="<div class='alert alert-danger'>Sorry,You Can not Enter This Page Directly</div>";
            redirect($theMsg,'');
        }
    }
    //end insert page
    
    //start edit page
    elseif ($do=='edit'){
        echo "<h1 class='text-center'>Edit Item</h1>'";
        $id=isset($_GET['id'])&is_numeric($_GET['id'])?intval($_GET['id']):0;
        $checkid=checkitem('item_ID','items',$id);
        if($checkid>0){
            $query="SELECT * FROM items WHERE item_ID=$id";
            $execute_query=mysqli_query($connection,$query);
            $rows=mysqli_fetch_array($execute_query);
?>
            <div class="container">
                <form action="items.php?do=update" method="post">
                    <div class="form-group row">
                        <input type="number" name="itemid" hidden value="<?php echo ($_GET[id]);?>">
                        <label class="col-sm-2 col-form-label" for="name">Name</label>
                        <input type="text" class="col-sm-10 form-control" id="name" name="name" value="<?php echo $rows[Name]?>" ><span class="asterisk">*</span>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="description">Description</label>
                        <input type="text" class="col-sm-10 form-control" id="description" name="description" value="<?php echo $rows[Description]?>" ><span class="asterisk">*</span>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="price">Price</label>
                        <input type="text" class="col-sm-10 form-control" id="price" name="price" value="<?php echo $rows[Price]?>" ><span class="asterisk">*</span>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="country">Country</label>
                        <input type="text" class="col-sm-10 form-control" id="country" name="country" value="<?php echo $rows[Countery_Made]?>" ><span class="asterisk">*</span>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="status">Status</label>
                        <div id="status" class="col-sm-10 select">
                        <select  name="status">
                            <option value="1" <?php if($rows[Status]==1) echo "selected";?> >New</option>
                            <option value="2" <?php if($rows[Status]==2) echo "selected";?> >Like New</option>
                            <option value="3" <?php if($rows[Status]==3) echo "selected";?> >Used</option>
                            <option value="4" <?php if($rows[Status]==4) echo "selected";?> >Very Old</option>
                        </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        
                        <label class="col-sm-2 col-form-label" for="mamber">Member</label>
                        <div id="member" class="col-sm-10 select">
                        <select  name="member">
                            <?php
                            $users=getallfrom("*","users","","","userId","ASC");
                            foreach ($users as $user) {
                                
                                echo "<option value='$user[0]'"; 
                                if($user[0]==$rows[Member_ID])
                                    { 
                                echo "selected";
                                    } 
                                echo ">$user[1]</option>";
                                
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="category">Category</label>
                        <div id="category" class="col-sm-10 select">
                        <select  name="category">
                            <?php
                            $cats=getallfrom("*","categories","WHERE Parent=0","","ID","ASC");
                            foreach($cats as $cat) {    
                                echo "<option value='$cat[0]'"; 
                                if($cat[0]==$rows[10])
                                    { 
                                echo "selected";
                                    } 
                                echo ">$cat[1]</option>";
                                $subcats=getallfrom("*","categories","WHERE Parent=$cat[0]","","ID","ASC");
                                foreach ($subcats as $subcat) {
                                    echo "<option value='$subcat[0]'"; 
                                    if($subcat[0]==$rows[10])
                                        { 
                                    echo "selected";
                                        } 
                                    echo ">---$subcat[1]</option>";
                                }
                            }
                            ?>
                        </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-2 col-form0-label" for="tags">Tags</label>
                        <input class="form-control col-md-10" id="tags" type="text" name="tags" placeholder="Seperate tags with ," 
                        value="<?php
                                    echo $rows['Tags'];
                                ?>"
                        >
                    </div>
                    <div class="form-group row">
                    <input type="submit" name="edit" value="Save Item" class="offset-md-2 btn btn-primary">
                </div>
                </form>
                
            </div>
        <?php }
        else{
            echo "<div class='alert alert-danger'>The ID Is Not Correct</div>";
        }
    }
    //end edit page

/*Start of Upadte page*/
    elseif($do=='update'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $itemid=$_POST['itemid'];
            $name=$_POST['name'];
            $description=$_POST['description'];
            $price=$_POST['price'];
            $country=$_POST['country'];
            $status=$_POST['status'];
            $member=$_POST['member'];
            $category=$_POST['category'];
            $tags=$_POST['tags'];
            if(empty($name)){
                $error['name']="Name Can not Be <strong>Empty</strong>";
            }
            if(empty($description)){
                $error['description']="description Can not Be <strong>Empty</strong>";
            }
            if(empty($price)){
                $error['price']="price Can not Be <strong>Empty</strong>";
            }
            if(empty($country)){
                $error['country']="country Can not Be <strong>Empty</strong>";
            }
            if(empty($error)){
                $query="UPDATE items SET Name='$name',Description='$description',Price='$price',Countery_Made='$country',Status='$status',Member_ID='$member',Cat_ID='$category',Tags='$tags' WHERE item_ID='$itemid'";
                if($execute_query=mysqli_query($connection,$query)){
                    $theMsg="<div class='alert alert-success'>One Item Updated</div>";
                    redirect($theMsg,$_SERVER['HTTP_REFERER']);
                }
                else{
                    $erMsg="<div class='alert alert-danger'>Database Error</div>";
                    redirect($erMsg,'',100);
                    
                }
            }
        }
        else{
            $theMsg="<div class='alert alert-danger'>Sorry, You can not Enter This Page Directly</div>";
            redirect($theMsg,'');
        }
    }
/*End of Upadte page*/
 
/*Start of Delete page*/
    
    elseif($do=='delete'){
    echo '<h1 class="text-center text-lg">Delete Items</h1>
  <div class="container">';
    $itemid=isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0;
    $check=checkitem('item_ID','items',$itemid);
    if($check>0){
       $delquery="DELETE FROM items WHERE item_ID=$itemid";
       if($execute_delquery=mysqli_query($connection,$delquery)){
           $theMsg= "<div class='alert alert-success '>Item deleted</div>";
           redirect($theMsg,$_SERVER['HTTP_REFERER'],0);
       } 
        else{
            echo "<div class='container'><strong class='alert alert-danger'>Database Error</strong></div>";
        }
        echo '</div>';
    }
    else{
        $erMsg= "<div class='alert alert-danger'>ID Not Found</div>";
           redirect($erMsg,'');
    }
}
/*Start of Delete page*/

/*Start of approve page*/
    
    elseif($do=='approve'){
        $itemid=isset($_GET['id'])&is_numeric($_GET['id'])?intval($_GET['id']):0;
        $checkitem=checkitem('item_ID','items',$itemid);
        if($checkitem>0){
            $query="UPDATE items SET Approve=1 WHERE item_ID=$itemid";
            if($execute_query=mysqli_query($connection,$query)){
                $theMsg="<div class='alert alert-success'>Item Approved</div>";
                redirect($theMsg,$_SERVER['HTTP_REFERER'],0);
            }
            else{
                $erMsg="<div class='alert alert-danger'>Database Error</div>";
                redirect($erMsg,'');
            }
        }
        else{
            $erMsg="<div class='alert alert-danger'>ID Not Found</div>";
            redirect($erMsg,'');
        }
    }
/*End of Approve page*/
    
include $templates.'footer.php';
}
else{
    header('location:index.php');
}
ob_end_flush();
?>