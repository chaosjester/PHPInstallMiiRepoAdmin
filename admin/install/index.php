<?php
session_start();

if(isset($_POST['repoconfig'])) {

  $reponame = $_POST['reponame'];
  $repoowner = $_POST['repoowner'];
  $repourl = $_POST['repourl'];
  $repoblurb = $_POST['repoblurb'];

  $repofile = "../../reposettings.php";
  $repofilecontent = '<?php $reponame = "'.$reponame.'"; $repoowner = "'.$repoowner.'"; $repourl = "'.$repourl.'"; $repoblurb = "'.$repoblurb.'";?>';
  file_put_contents($repofile, $repofilecontent);

  if (file_exists($repofile)){
    $message = 'reposettings.php created<br><br><a href ="#step2" class="btn waves-effect waves-light">Proceed to Step 2</a>';
  } else if (!file_exists($repofile)) {
    $error = "reposettings.php failed to create, check your webserver logs for errors";
  }

} //repoconfig

if (isset($_POST['dbconfig'])){

  include("../../reposettings.php");

  $dbaddress = $_POST['dbaddress'];
  $dbusername = $_POST['dbusername'];
  $dbpassword = $_POST['dbpassword'];
  $dbname = $_POST['dbname'];

  $dbfile = "../includes/connection.php";
  $dbfilecontent = '<?php $dbaddress = "'.$dbaddress.'"; $dbusername = "'.$dbusername.'"; $dbpassword = "'.$dbpassword.'"; $dbname = "'.$dbname.'";
  
  $link = new mysqli($dbaddress, $dbusername, $dbpassword, $dbname);

  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  }

  ?>';
  file_put_contents($dbfile, $dbfilecontent);

  if (file_exists($dbfile)){
    $message = 'Database settings created<br>';
  } else if (!file_exists($dbfile)) {
    $error = "Database configuration file failed to create, check your webserver logs for errors";
  }

  $conn = new mysqli($dbaddress, $dbusername, $dbpassword);

  $sql = 'CREATE DATABASE '.$dbname;

  $result = $conn->query($sql);

  if ($conn->query($sql) === TRUE) {

    $message = $message.'Database '.$dbname.' created';
  } else {

    $error = "There was an error creating the database<br>" . $conn->error."<br>Sometimes the database already exists, check your SQL server<br>If the database has not been created, create it manually and add permissions to the supplied user and try again<br><br>";
  }

$conn = new mysqli($dbaddress, $dbusername, $dbpassword, $dbname);

  $sql = "CREATE TABLE users (
    id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    password VARCHAR(200) NOT NULL
    )";

if ($conn->query($sql) === TRUE) {
  $message = $message."Table `users` created<br>";
} else {
  $error = $error.'Table `users` not created<br>'. $conn->error.'<br><br>';
  
}


$sql = "CREATE TABLE packages (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  short_description VARCHAR(200),
  author VARCHAR(30) NOT NULL,
  category VARCHAR(50),
  website VARCHAR(200),
  type VARCHAR(50),
  version VARCHAR(50),
  dl_path VARCHAR(200),
  info_path VARCHAR(200)
  )";

if ($conn->query($sql) === TRUE) {
  $message = $message.'Table `packages` created<br>';
} else {
  $error = $error.'Table `packages` not created<br>'. $conn->error.'<br>';
  
}
$sql = "CREATE TABLE repos (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  url VARCHAR(200)
  );";


if ($conn->query($sql) === TRUE) {

  $message = $message.'Table `repos` created<br><br><a href ="#step3" class="btn waves-effect waves-light">Proceed to Step 3</a>';
  
  $reponame = mysqli_real_escape_string($conn, $reponame);
  $repourl = mysqli_real_escape_string($conn, $repourl);

  $sql = "INSERT INTO repos (`name`, `url`)
  VALUES ('$reponame','$repourl')";

$conn->query($sql);

} else {
  $error = $error.'Table `repos` not created<br>'. $conn->error.'<br><br>This may not be a problem, if the error states that the tables already exist, you should be right.<br>Check your SQL server to see if the database and tables are present.<br><br><a href ="#step3" class="btn waves-effect waves-light">Proceed to Step 3</a>';
  
}

$conn->close();



} //dbconfig


if(isset($_POST['register'])){

  include ("../includes/connection.php");

  function isUnique($email){
    $query = "SELECT * FROM users WHERE email='$email'";
    global $link;

    $result = $link->query($query);

    if($result->num_rows > 0){
      return false;
    }
    else return true;
  }

  $_SESSION['name'] = $_POST['name'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['password'] = $_POST['password'];
  $_SESSION['confirm_password'] = $_POST['confirm_password'];

  if(strlen($_POST['name'])<3){
    $error ="Name must be at least 3 chatacters long";
  }
  else if(strlen($_POST['password'])<8){
    $error ="Password should be at least 8 characters";
  }
  else if(strlen($_POST['confirm_password'])<8){
    $error ="Confirm Password should be at least 8 characters";
  }
  else if($_POST['password'] != $_POST['confirm_password']){
    $error ="Password and Confirm Password do not match";
  }
  else if(!isUnique($_POST['email'])){
    $error ="Email in use, please use another one";

  }
  else {
    $name = mysqli_real_escape_string($link , $_POST['name']);
    $email = mysqli_real_escape_string($link , $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (name,email,password) VALUES('$name','$email','$password')";

    if ($link->query($query) === TRUE) {
      $message = 'Account Created<br>If you do not require any more users, you should delete the install directory now<br><br><a href ="../" class="btn waves-effect waves-light">Proceed to Login</a>';
    } else {
      $error = 'User not created<br>'. $link->error;

    }

  }
} //register

?>

<!DOCTYPE html>
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="../index.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Repo Admin Install</title>
</head>

<body>
  <header>
    <nav>
      <div class="nav-wrapper blue-grey darken-4">
        <a class="brand-logo center">PHPInstallMiiRepoAdmin Installer</a>
      </div>
    </nav>
  </header>     
  <main>
    <br>
    <div class="container" id="top">
      <?php if(isset($error)) { ?>
      <div class="row">
        <div class="col s12 m6 offset-m3 center-align">
          <div class="card-panel red">
            <p class="white-text"><?php echo $error; ?></p>
          </div>       
        </div>
      </div>
      <?php } ?>
      <?php if(isset($message)) { ?>
      <div class="row">
        <div class="col s12 m6 offset-m3 center-align">
          <div class="card-panel green">
            <p class="white-text"><?php echo $message; ?></p>
          </div>       
        </div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col s12 m10 offset-m1 center-align">
          <h3>Step 1: Repo Settings</h3>
          <p>These details are used on the index page of your repo</p>
          <form method="post">
            <div class="input-field col s12 m6">
              <input required name="reponame" type="text">
              <label for="reponame">Repo Name</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="repoowner" type="text">
              <label for="repoowner">Repo Owner</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="repourl" type="text">
              <label for="repourl">Repo URL</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="repoblurb" type="text">
              <label for="repoblurb">Repo Blurb Text</label>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="repoconfig">Create Repo Settings
              <i class="material-icons left">done</i>
            </button>
          </form>
        </div>
      </div>
      <div class="row" id="step2">
        <div class="col s12 m10 offset-m1 center-align">
          <h3>Step 2: Database settings</h3>
          <p>Please create a MySQL user before this step<br>If you are on a shared host, you can do this in your hosting control panel<br>For shared hosts, the database name and user format is usually "hostingusername_databasename"<br>This script should create the database for you so only a user is required<br>If the database fails to create, also create the database on your hosting control panel<br>The server address is usually "localhost"</p>
          <form method="post">
            <div class="input-field col s12 m6">
              <input required name="dbaddress" type="text">
              <label for="dbaddress">MySQL Server Address</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="dbusername" type="text">
              <label for="dbusername">Database Username</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="dbpassword" type="password">
              <label for="dbpassword">Database Password</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="dbname" type="text">
              <label for="dbname">Database Name</label>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="dbconfig">Create Database Configuration
              <i class="material-icons left">done</i>
            </button>
          </form>
        </div>
      </div>
      <div class="row" id="step3">
        <div class="col s12 m10 offset-m1 center-align">
          <h3>Step 3: Admin User Creation</h3>
          <p>Fill out the form with the deisred details<br>Multiple Admins can be added if needed</p>
          <form method="post">
           <div class="row">
             <div class="input-field col s12 m6">
               <input type="text" name="name" required value="<?php echo @$_SESSION['name']; ?>">
               <label for="name">Name</label>
             </div>
             <div class="input-field col s12 m6">
               <input name="email" type="email" class="validate" required value="<?php echo @$_SESSION['email']; ?>">
               <label for="email">Email</label>
             </div>
             <div class="input-field col s12 m6">
               <input name="password" type="password" class="validate" required value="<?php echo @$_SESSION['password']; ?>">
               <label for="password">Password</label>
             </div>
             <div class="input-field col s12 m6">
               <input name="confirm_password" type="password" class="validate" required value="<?php echo @$_SESSION['confirm_password']; ?>">
               <label for="confirm_password">Confirm Password</label>
             </div>
             <div class="col s12 input-field">
               <button class="btn waves-effect waves-light" type="submit" name="register">Register
                 <i class="material-icons right">send</i>
               </button>                      
             </div>
           </div>
         </form>
       </div>
     </div>
   </div>
 </div>
</div>
</main>
<footer class="page-footer blue-grey darken-3">
  <div class="container ">
    <div class="row ">
      <div class="col l6 s12 ">
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
<script src="custom.js"></script>
</body>
</html>