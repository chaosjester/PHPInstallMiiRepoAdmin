<?php
session_start();

require ("../reposettings.php");
include ("includes/connection.php");

function isUnique($email){
  $query = "SELECT * FROM users WHERE email='$email'";
  global $link;

  $result = $link->query($query);

  if($result->num_rows > 0){
    return false;
  }
  else return true;
}

if(isset($_POST['register'])){
  $_SESSION['name'] = $_POST['name'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['password'] = $_POST['password'];
  $_SESSION['confirm_password'] = $_POST['confirm_password'];

  if(strlen($_POST['name'])<3){
    header("Location:register.php?err=".urlencode("Name must be at least 3 chatacters long"));
    exit();
  }
    else if(strlen($_POST['password'])<8){
      header("Location:register.php?err=".urlencode("Password should be at least 8 characters"));
    exit();
  }
    else if(strlen($_POST['confirm_password'])<8){
      header("Location:register.php?err=".urlencode("Confirm Password should be at least 8 characters"));
    exit();
  }
  else if($_POST['password'] != $_POST['confirm_password']){
    header("Location:register.php?err=".urlencode("Password and Confirm Password do not match"));
    exit();
  }
  else if(!isUnique($_POST['email'])){
    header("Location:register.php?err=".urlencode("Email in use, please use another one"));
    exit();  
  }
  else {
    $name = mysqli_real_escape_string($link , $_POST['name']);
    $email = mysqli_real_escape_string($link , $_POST['email']);
    $password = mysqli_real_escape_string($link , md5(md5($_POST['email'].$_POST['password'])));

    $query = "INSERT INTO users (name,email,password) VALUES('$name','$email','$password')";

    $link->query($query);

    header("Location:index.php?success=".urlencode("Account created, please log in"));
    exit();
  }
}



?>
<!DOCTYPE html>
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="../custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Register</title>
</head>

<body>
  <header>
    <nav>
      <div class="nav-wrapper blue-grey darken-4">
        <a class="brand-logo center"><?php echo $reponame ?></a>
        <ul class="right hide-on-med-and-down">
          <li><a href="../admin/"><i class="material-icons right">view_module</i>Admin Login</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <main>
    <div class="container">
      <div class="row">
        <div class="col s12 m6 offset-m3 center-align">
        <h3>Repo Admin Setup</h3>
        <hr>
        <p>Please enter the details below to create your admin account.<br>If you do not require more than 1 admin account, it is advisable to delete this page as soon as your account is created and the Database configured.<br>Before proceeding please ensure you have created your MySQL Database and modified the admin/includes/connection.php file with the details. </p>
        <hr>
        <p>Please click here to create the required database tables</p>
        <a href="create_tables.php" class="btn waves-effect waves-light" target="_blank">Create tables</a>
        <?php if(isset($_GET['err'])){ ?>
        <div class="row">
          <div class="col s12 center-align">
          <div class="card-panel dismissable red accent-4 white-text">
          <?php echo $_GET['err'] ?>
          </div>
          </div>
        </div>
        <?php }?>
         <form action="register.php" method="post">
          <div class="row">
          <div class="input-field col s12">
            <input type="text" name="name" required value="<?php echo @$_SESSION['name']; ?>">
            <label for="name">Name</label>
          </div>
            <div class="input-field col s12">
              <input name="email" type="email" class="validate" required value="<?php echo @$_SESSION['email']; ?>">
              <label for="email">Email</label>
            </div>
            <div class="input-field col s12">
              <input name="password" type="password" class="validate" required value="<?php echo @$_SESSION['password']; ?>">
              <label for="password">Password</label>
            </div>
            <div class="input-field col s12">
              <input name="confirm_password" type="password" class="validate" required value="<?php echo @$_SESSION['confirm_password']; ?>">
              <label for="confirm_password">Confirm Password</label>
            </div>
            <div class="col s12 input-field left-align">
              <button class="btn waves-effect waves-light" type="submit" name="register">Register
                <i class="material-icons right">send</i>
              </button>                      
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<footer class="page-footer blue-grey darken-3">
  <div class="container ">
    <div class="row ">
      <div class="col l6 s12 ">
        <h5 class="white-text">Repo Provided by <?php echo $repoowner ?></h5>
        <p class="grey-text text-lighten-4"><?php echo $repoblurb ?></p>
        <p class="grey-text text-lighten-3">PHPInstallMiiRepo by ChaosJester and LiquidFenrir</p>
      </div>
    </div>
  </div>
  <div class="footer-copyright blue-grey darken-2">
    <div class="container">
      Created with PHP InstallMii Repo creator.
      <a class="grey-text text-lighten-4 right" href="https://github.com/chaosjester/PHPInstallMiiRepo" target="_blank">Project GitHub page</a>
    </div>
  </div>
</footer>



<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>
</html>