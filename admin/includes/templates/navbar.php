<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <a class="navbar-brand" href="controlpanel.php">ECOMMERCE SHOP</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
    <span class="navbar-toggler-icon"></span>
  </button>
     
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav">
           <li class="nav-item ">
        <a class="nav-link" href="categories.php"><?php echo lang('CATEGOREIS') ?> </a>
      </li>
          <li class="nav-item ">
        <a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?> </a>
      </li>
          <li class="nav-item ">
        <a class="nav-link" href="members.php"><?php echo lang('MEMBERS') ?> </a>
      </li>
          <li class="nav-item ">
        <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?> </a>
      </li>
          <li class="nav-item ">
        <a class="nav-link" href="#"><?php echo lang('STATISTCS') ?> </a>
      </li>
          <li class="nav-item ">
        <a class="nav-link" href="#"><?php echo lang('LOGS') ?> </a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
    
   
      <li class="nav-item dropdown ">
          
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Hossam
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="drop">
          <a class="dropdown-item" href="../index.php">Visit Shop</a>
          <a class="dropdown-item" href="members.php?do=edit&userId=<?php echo $_SESSION['userId']?>">Edit Profile</a>
          <a class="dropdown-item" href="#">Settings</a>
        
          <a class="dropdown-item" href="logout.php">Log Out</a>
        </div>
              
      </li>
    
    </ul>

  </div>
</nav>
