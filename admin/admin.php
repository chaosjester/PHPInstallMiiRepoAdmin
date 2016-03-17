<?php
session_start();
require ("../reposettings.php");
include ("includes/connection.php");

if(!isset($_SESSION['name']))
{
 header("Location: index.php");
}
$res=mysql_query("SELECT * FROM users WHERE name=".$_SESSION['name']);
$userRow=mysql_fetch_array($res);
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
  <title>Repo Admin</title>
</head>

<body>
<header>
<nav>
  <div class="nav-wrapper blue-grey darken-4">
    <a class="brand-logo center"><?php echo $reponame ?></a>
    <ul class="right hide-on-med-and-down">
      <li>Logged in as <?php echo $_SESSION['name']; ?></li>
      <li><a href="logout.php?logout">Log Out<i class="material-icons left">input</i></a></li>
    </ul>
  </div>
</nav>
</header>
<main>
  <br><br><br><br>
  <div class="container">
    <div class="row">
      <div class="col s12 m4 offset-m4 center-align">
       <div class="card-panel blue-grey lighten-3">
          <span class="white-text">Welcome <?php echo $_SESSION['name']; ?>
          </span>
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