<?php
session_start();

require ("../reposettings.php");
include ("includes/connection.php");

if(!isset($_SESSION['name']))
{
 header("Location: index.php?err=".urlencode("Either you are not logged in or your username and/or password are incorrect. Please try again."));
 exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Packages</title>
</head>

<body>
  <header>
    <nav>
      <div class="nav-wrapper blue-grey darken-4">
        <a class="brand-logo center"><?php echo $reponame ?></a>
        <ul class="right hide-on-med-and-down">
          <li><div class="chip"><?php echo $_SESSION['name']; ?></div></li>
          <li><a href="logout.php?logout">Log Out<i class="material-icons right">input</i></a></li>
        </ul>
        <ul id="slide-out" class="side-nav fixed">
          <li><a href="admin.php">Home</a></li>
          <li><a href="viewpackage.php" class="active">View Packages</a></li>
          <li><a href="addcustom.php">Add Custom Package</a></li>
          <li><a href="deletepackage.php">Delete Packages</a></li>
          <li><a href="generatejson.php">Generate Package Lists</a></li>
        </ul>
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
      </div>
    </nav>
  </header>     
  <main>
    <br>
    <div class="container">
      <div class="row">
        <div class="col s12 m12 center-align">
            <table class="bordered highlight">
              <tr>
                <th></th>
                <th>Name</th>
                <th>Description</th>
                <th>Author</th>
                <th>Category</th>
                <th>Type</th>
                <th>Version</th>
                <th>Website</th>
                <th>Info Path</th>
                <th>Download Path</th>
              </tr>
              <?php 

              $query="SELECT * FROM packages";
              $results = $link->query($query);

              while ($row = mysqli_fetch_array($results)) { ?>
              <tr>
                <td>
                    <a href="modifypackagepage.php?id=<?php echo $row['id']; ?>" class="waves-effect waves-light btn">Modify</a>
                </td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['short_description']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['version']; ?></td>
                <td><?php echo $row['website']; ?></td>
                <td><?php echo $row['info_path']; ?></td>
                <td><?php echo $row['dl_path']; ?></td>
              </tr>
              <?php } ?>
            </table>
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
  <script src="custom.js"></script>
</body>
</html>