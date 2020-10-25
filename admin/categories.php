
<?php
/*
===========================================
== manage categories page
== add/delete/edit members from here
===========================================
*/
ob_start();
session_start();

$pageTitle='categories';
if(isset($_SESSION['username'])){
include "init.php";
$do=isset($_GET['do'])?$_GET['do']:'manage';
if($do=='manage'){
    //ordering categories 
    $countCategories=countElements1('ID','categories');
    if($countCategories>0){
    $order="ASC";
    $order_opt=array("ASC","DESC");
    if(isset($_GET['order']) && in_array($_GET['order'],$order_opt)){
        $order=$_GET['order'];
    }
    $query="SELECT * FROM categories WHERE Parent=0 ORDER BY ordering $order";
    $execute_query=mysqli_query($connection,$query);
?>
  <div class="container categories">    
    <h1 class="text-center">Manage Categories</h1>
    <div class="card">
        <div class="card-header">
          <i class="fas fa-edit"></i>  Manage Categories
            <span class="float-right"><i class="fas fa-sort"></i>Ordering:[<a class="<?php if($order=='ASC') echo "order";?>" href="?order=ASC">ASC</a> |
                <a class="<?php if($order=='DESC') echo "order";?>" href="?order=DESC">DESC</a>] 
                <i class="fas fa-eye ey"></i>Option:[<span class="active option" data-view="full">Full</span>|
                <span class="option">Classic</span>]
            </span>
            
        </div>
        <div class="card-body"><?php
            while($rows=mysqli_fetch_array($execute_query))
                 {?>
                    <div class='cat'>
                        <div class='hidden_buttons'>
                        <a href='categories.php?do=edit&catid=<?php echo $rows['ID']?>' class='btn btn-primary'><i class='fas fa-edit'></i>Edit</a>
                        <a href='categories.php?do=delete&catid=<?php echo $rows['ID']?>' class='confirm btn btn-danger'><i class='fas fa-window-close'></i>Delete</a>
                        </div>
                        <h3><?php echo $rows[Name] ?></h3>
                        <div class='cat_info'>
                            <?php
                             if($rows['Description']=="") echo "<p>"."no info about this category"."</p>"; else echo "<p>".$rows['Description']."</p>";
                            if($rows['Visibility']==1) echo "<span class='visib'><i class='fas fa-eye'></i>Hidden</span>";
                            if($rows['Allow-comment']==1) echo "<span class='com'><i class='fas fa-window-close'></i>Comments Disabled</span>";
                            if($rows['Allow-add']==1) echo "<span class='advirtise'><i class='fas fa-window-close'></i>Ads Disabled</span>";
                            $subcats=getallfrom("*","categories","WHERE Parent=$rows[ID]","","ID","ASC");
                            if(!empty($subcats)){
                                echo "<div class='subcat'>";
                                echo "<h6>Child Categories</h6>";
                                foreach($subcats as $subcat){?>
                                    <ul class='list-unstyled'>
                                     <li class="child-delete">
                                         <a href='categories.php?do=edit&catid=<?php echo $subcat[0]?>'>- <?php echo $subcat[1] ?> </a> 
                                         <a href='categories.php?do=delete&catid=<?php echo $subcat[0]?>' class='confirm show-delete'>Delete</a>
                                     </li>
                                    </ul>
                              <?php  } ?> 
                                </div>
                        <?php    } ?>
                             
                        </div>
                    </div>
                    <hr>
                 <?php }
            ?></div>
    </div>
       <a href="categories.php?do=add" class="addCateg btn btn-primary"><i class="fas fa-plus"></i>Add Category</a>
  </div>      
 <?php 
    }
    else{
        echo "<div class='container'>";
        echo "<div class='alert alert-info'>There Is No Categories To Show</div>";  
        echo "<a href='categories.php?do=add' class='btn btn-primary'><i class='fas fa-plus'></i> New Category</a>";     
        echo "</div>";
        }
}

//Edit Page
elseif($do=='edit'){
    $catid=intval($_GET['catid']);
    $catinfo=getallfrom("*","categories","WHERE ID=$catid","","ID","ASC");
    ?>
     <h1 class="text-center text-lg">Update Category</h1>
  <div class="container">
  <form class="form-horizontal" action="?do=update" method="post">

      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Name</label>
          <input type="text"  class="col-sm-10  form-control" autocomplete="off" name="name" placeholder="Enter Category Name" value="<?php echo $catinfo[0][1]?>" required="required">
      </div>
      <input type="text"  class="col-sm-10  form-control" hidden name="id" value="<?php echo $catinfo[0][0]?>">
      <div class=" form-group form-group-lg row">
          <label for="describe" class="col-sm-2 col-form-label">Description</label>
          <input type="text" value="<?php echo $catinfo[0][2]?>" placeholder="Enter Category Description" id="describe" class="col-sm-10  form-control"  name="description" >
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Ordering</label>
          <input type="text"  class="col-sm-10  form-control" autocomplete="off" placeholder="Enter Category Ordering" value="<?php echo $catinfo[0][3]?>" name="ordering" >
      </div>
      <div class="form-group row">
          <label for="parent" class="col-md-2 col-form-label">Parent?</label>
          <div class="col-md-10">
          <select name="parent" id="parent" class="form-control">
              <option value="0">None</option>
              <?php
                $query="SELECT * FROM categories WHERE Parent=0 ORDER BY ordering $order";
                $execute_query=mysqli_query($connection,$query);
                while($rows=mysqli_fetch_array($execute_query)){
                    echo "<option value='$rows[ID]'"; 
                    if($rows[ID]==$catinfo[0][7]) echo 'selected'; 
                    echo ">$rows[Name]</option>";
                }
              ?>
          </select>
          </div>
      </div>
      <div class=" form-group form-group-lg row ">
          <label class="col-sm-2 col-form-label">Visible</label>
          <div class="col-sm-10 ">
          <div class="row form-check ">
            <input id="vis-yes" class="form-check-input" type="radio"   name="visible" value="0" checked>
            <label for="vis-yes" class="form-check-label">Yes</label>
          </div>
          <div class="row form-check">
            <input id="vis-no" type="radio" class="form-check-input"  name="visible" value="1" >
            <label for="vis-no" class="form-check-label">No</label>
          </div>
              </div>
      </div>
      <div class=" form-group form-group-lg  row">
          <label class="col-sm-2 col-form-label">Allow Commenting</label>
          <div class="col-sm-10">
          <div class="row form-check">
            <input id="comm-yes" type="radio" class="form-check-input" name="comment" value="0" checked>
            <label for="comm-yes" class="form-check-label" >Yes</label>
          </div>
          <div class="row form-check">
            <input id="comm-no" type="radio" class="form-check-input" name="comment" value="1" >
            <label for="comm-no" class="form-check-label">No</label>
          </div>
              </div>
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Allow Ads</label>
          <div class="col-sm-10">
          <div class="row form-check">
            <input id="ad-yes" type="radio" class="form-check-input" name="ads" value="0" checked>
            <label for="ad-yes" class="form-check-label" >Yes</label>
          </div>
          <div class="row form-check">
            <input id="ad-no" type="radio" class="form-check-input" name="ads" value="1" >
            <label for="ad-no" class="form-check-label">No</label>
          </div>
              </div>
      </div>
      <div calss="row ">
      
          <input type="submit" name='Addcategory'  value="Update Category" class="offset-md-2 btn btn-primary  ">
              
    </div>
</form>
      </div> 
<?php
 }

//update page    
elseif($do=='update'){
    echo "<h1 class='text-center'>welcome</h1>";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $id=$_POST['id'];
        $name=$_POST['name'];
        $describe=$_POST['description'];
        $ordering=$_POST['ordering'];
        $parent=$_POST['parent'];
        $visibility=$_POST['visible'];
        $comment=$_POST['comment'];
        $ads=$_POST['ads'];
        $checkCateg=checkitem1('Name','categories',$name,$_GET[catid]);
        if($checkCateg>0){
                $theMsg= "<div class='alert alert-danger'>The Category You Entered Is Already exist</div>";
                redirect($theMsg,$_SERVER['HTTP_REFERER'],3);
        }
        else{
                $query="UPDATE categories SET Name='$name' , Description='$describe' , Ordering=$ordering,Parent=$parent , Visibility=$visibility , `Allow-comment`=$comment , `Allow-add`=$ads WHERE ID=$id";
                $theMsg="<div class='alert alert-success'>one Category Updated</div>";
                if($execute_query=mysqli_query($connection,$query))
                    {   
                    redirect($theMsg,$_SERVER['HTTP_REFERER'],3);
                    }
                else{
                    echo "<div class='container alert alert-danger'>Database Error</div>";
                    }
    
            }   
    }
    else{
        $theMsg="<div class='alert alert-danger'>this page can not entered directly</div>";
        redirect($theMsg,'categories.php',3);
    }
}
    
    
//add page    
else if($do=='add'){?>
     <h1 class="text-center text-lg">Add New Category</h1>
  <div class="container">
  <form class="form-horizontal" action="?do=insert" method="post">

      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Name</label>
          <input type="text"  class="col-sm-10  form-control" autocomplete="off" name="name" placeholder="Enter Category Name" required="required">
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Description</label>
          <input type="text" placeholder="Enter Category Description" class="col-sm-10  form-control"  name="description" >
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Ordering</label>
          <input type="text"  class="col-sm-10  form-control" autocomplete="off" placeholder="Enter Category Ordering" name="ordering" >
      </div>
      <!-- start parent categories -->
      <div class="form-group row">
          <label for="parent" class="col-form-label col-sm-2">Parent?</label>
          <div class="col-sm-10">
              <select  class="form-control" id="parent" name="parent">
                  <option value="0">None</option>
                  <?php
                    $allcats=getallfrom("*","categories","WHERE Parent=0","","ID","DESC");
                    foreach($allcats as $cat){
                        echo "<option value='$cat[0]'>$cat[1]</option>";
                    }
                  ?>
              </select>
          </div>
      </div>
      <!-- end parent categories -->
      <div class=" form-group form-group-lg row ">
          <label class="col-sm-2 col-form-label">Visible</label>
          <div class="col-sm-10 ">
          <div class="row form-check ">
            <input id="vis-yes" class="form-check-input" type="radio"   name="visible" value="0" checked>
            <label for="vis-yes" class="form-check-label">Yes</label>
          </div>
          <div class="row form-check">
            <input id="vis-no" type="radio" class="form-check-input"  name="visible" value="1" >
            <label for="vis-no" class="form-check-label">No</label>
          </div>
              </div>
      </div>
      <div class=" form-group form-group-lg  row">
          <label class="col-sm-2 col-form-label">Allow Commenting</label>
          <div class="col-sm-10">
          <div class="row form-check">
            <input id="comm-yes" type="radio" class="form-check-input" name="comment" value="0" checked>
            <label for="comm-yes" class="form-check-label" >Yes</label>
          </div>
          <div class="row form-check">
            <input id="comm-no" type="radio" class="form-check-input" name="comment" value="1" >
            <label for="comm-no" class="form-check-label">No</label>
          </div>
              </div>
      </div>
      <div class=" form-group form-group-lg row">
          <label class="col-sm-2 col-form-label">Allow Ads</label>
          <div class="col-sm-10">
          <div class="row form-check">
            <input id="ad-yes" type="radio" class="form-check-input" name="ads" value="0" checked>
            <label for="ad-yes" class="form-check-label" >Yes</label>
          </div>
          <div class="row form-check">
            <input id="ad-no" type="radio" class="form-check-input" name="ads" value="1" >
            <label for="ad-no" class="form-check-label">No</label>
          </div>
              </div>
      </div>
      <div calss="row ">
      
          <input type="submit" name='Addcategory'  value="Add Category" class="offset-md-2 btn btn-primary  ">
              
    </div>
</form>
      </div> 
<?php
    
 }
    
//insert page    
else if($do=='insert'){
    echo "<h1 class='text-center'>welcome</h1>";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $name=$_POST['name'];
        $describe=$_POST['description'];
        $ordering=$_POST['ordering'];
        $parent=$_POST['parent'];
        $visibility=$_POST['visible'];
        $comment=$_POST['comment'];
        $ads=$_POST['ads'];
        $checkCateg=checkitem('Name','categories',$name);
        if($checkCateg>0){
                $theMsg= "<div class='alert alert-danger'>The Category You Entered Is Already exist</div>";
                redirect($theMsg,$_SERVER['HTTP_REFERER'],3);
        }
        else{
                $query="INSERT INTO `categories`(`Name`, `Description`, `Ordering`,`Parent`, `Visibility`, `Allow-comment`, `Allow-add`) VALUES ('$name','$describe','$ordering','$parent','$visibility',$comment,'$ads')";
                $theMsg="<div class='alert alert-success'>one Category inserted</div>";
                if($execute_query=mysqli_query($connection,$query))
                    {   
                    redirect($theMsg,$_SERVER['HTTP_REFERER'],3);
                    }
                else{
                    echo "<div class='container alert alert-danger'>Database Error</div>";
                    }
    
            }   
    }
    else{
        $theMsg="<div class='alert alert-danger'>this page can not entered directly</div>";
        redirect($theMsg,'categories.php',3);
    }
} 
    
//Delete page   
elseif($do=='delete'){
    
    echo "<h1 class='text-center'>Delete Category</h1>";
    $catid=(isset($_GET['catid'])&&is_numeric($_GET['catid']))?intval($_GET['catid']):0;
    $checkId=checkitem('ID','categories',$catid);
    $theMsg="<div class='alert alert-success'>The Category Deleted Succefully</div>";
    $erMsg="<div class='alert alert-danger'>Database Error</div>";
    if($checkId>0){
        $query="DELETE FROM categories WHERE ID=$catid";
        if(mysqli_query($connection,$query)){
            redirect($theMsg,$_SERVER['HTTP_REFERER']);
        }
        else{
           redirect($erMsg,$_SERVER['HTTP_REFERER']); 
        }
        }
    }
include $templates."footer.php";
}

else{ 
header('location:index.php');
}
ob_end_flush();
?>