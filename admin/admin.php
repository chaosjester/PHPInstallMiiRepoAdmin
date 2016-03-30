<?php
session_start();

if (!file_exists("./includes/connection.php")){
  header('Location:./install');
  } else {

require ("../reposettings.php");
include ("includes/connection.php");
}

if(!isset($_SESSION['name']))
{
  $_SESSION['error'] = "Either you are not logged in or your username and/or password are incorrect. Please try again.";
  header("Location: index.php");
  exit();
}

$result = $link->query("SELECT COUNT(*) FROM `packages`");
$row = $result->fetch_row();

?><!DOCTYPE html>
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Repo Admin</title>
</head>

<body>


<?php include('header.php'); ?>
   
<main>
  <br>
  <div class="container">
    <div class="row">
      <div class="col s12 m10 offset-m1 center-align">
        <?php if(file_exists("../admin/install/index.php")){ ?>
        <div class="row">
          <div class="col s12 center-align">
            <div class="card-panel dismissable orange white-text">
              <i class="large material-icons">report_problem</i><h3>WARNING!</h3>
              <p>Instalation files still exist<br>if you want to add more admin accounts, do so now and remove the /admin/install directory<br>This message will dissapear when the files have been deleted</p>
            </div>
          </div>
        </div>
        <?php }?>
        <?php if(!file_exists("../repo.list")){ ?>
        <div class="row">
          <div class="col s12 center-align">
            <div class="card-panel dismissable orange white-text">
              <i class="large material-icons">report_problem</i><h3>WARNING!</h3>
              <p>You have not generated a repo.list file<br>Please proceed to the "Manage Repo List" page to create one.</p>
              <a href="repolist.php" class="btn waves-effect waves-light">Manage Repo List</a>
            </div>
          </div>
        </div>
        <?php }?>
        <?php if(!file_exists("../packages.json")){ ?>
        <div class="row">
          <div class="col s12 center-align">
            <div class="card-panel dismissable orange white-text">
              <i class="large material-icons">report_problem</i><h3>WARNING!</h3>
              <p>You have not generated a packages.json file<br>Please proceed to the "Generate Package Lists" page to create one.</p>
              <a href="generatejson.php" class="btn waves-effect waves-light">Generate Package Lists</a>
            </div>
          </div>
        </div>
        <?php }?>
        <div class="card blue-grey darken-1">
         <div class="card-content white-text">
           <span class="card-title">Welcome <?php echo $_SESSION['name'];?></span>
           <p>Welcome to your InstallMii Repo Admin page.<br>You currently have <?php echo $row[0]; ?> Package(s) configured.</p>
         </div>
         <div class="card-action">
           <a href="viewpackage.php">View Packages</a>
           <a href="generatejson.php">Generate Repo Files</a>
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
        <h5 class="white-text">Repo Provided by <?php echo $repoowner ?></h5>
        <p class="grey-text text-lighten-4"><?php echo $repoblurb ?></p>
        <p class="grey-text text-lighten-3">PHPInstallMiiRepo by ChaosJester and LiquidFenrir</p>
      </div>
    </div>
  </div>
  <div class="footer-copyright blue-grey darken-2">
    <div class="container">
        Created with PHP InstallMii Repo Admin.
        <a class="grey-text text-lighten-4 right" href="https://github.com/chaosjester/PHPInstallMiiRepoAdmin" target="_blank">Project GitHub page</a>
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