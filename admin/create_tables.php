<?php

require ("../reposettings.php");
include ("includes/connection.php");

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
<title>Create database tables</title>
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
  <br/><br/>
    <div class="container">
      <div class="row">
        <div class="col s12 m6 offset-m3 center-align">

<?php


        		$sql = "CREATE TABLE users (
        		id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        		name VARCHAR(200) NOT NULL,
        		email VARCHAR(200) NOT NULL,
        		password VARCHAR(200) NOT NULL
        		)";

        		if ($link->query($sql) === TRUE) {
        		    echo "<div class='row'>
        		    <div class='col s12 center-align'>
        		    <div class='card-panel dismissable green white-text'><p>Table Users created successfully</p>
        		    </div></div></div>";
        		} else {
        		    echo "<div class='row'>
          <div class='col s12 center-align'>
          <div class='card-panel dismissable red accent-4 white-text'><p>Error creating table: " . $link->error . "<br>This may not be an issue, it may just be that you have alreade done this step!<br>Check your SQL database to confirm that it contains tables</p></div></div></div>";
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

        		if ($link->query($sql) === TRUE) {
        		    echo "<div class='row'>
        		    <div class='col s12 center-align'>
        		    <div class='card-panel dismissable green white-text'><p>Table Packages created successfully</p>
        		    </div></div></div>";
        		} else {
        		    echo "<div class='row'>
          <div class='col s12 center-align'>
          <div class='card-panel dismissable red accent-4 white-text'><p>Error creating table: " . $link->error . "<br>This may not be an issue, it may just be that you have alreade done this step!<br>Check your SQL database to confirm that it contains tables</p></div></div></div>";
        		}

        		$link->close();
        	?>
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