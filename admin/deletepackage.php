<?php
session_start();

require ("../reposettings.php");
include ("includes/connection.php");

if(!isset($_SESSION['name']))
{
  $_SESSION['error'] = "Either you are not logged in or your username and/or password are incorrect. Please try again.";
 header("Location: index.php");
 exit();
}

if(isset($_POST['delete'])){

  if(!empty($_POST['checkbox'])){
    $checks = implode("','", $_POST['checkbox']);
    $query = "DELETE FROM `packages` WHERE `id` IN ('$checks')";
    $link->query($query);
    $message = count($_POST['checkbox'])." Package(s) deleted<br>Redirecting back to package list in 3 seconds";
    $link->close();
  }

}


?>

<!DOCTYPE html>
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Delete Packages</title>
</head>

<body>
<?php include('header.php'); ?>   
  <main>
    <br>
    <div class="container">
      <div class="row">
        <div class="col s12 m12 center-align">
          <?php if(isset($_POST['delete'])){ ?>
          <div class="row">
            <div class="col s12 m6 offset-m3">
              <div class="card-panel green">
                <span class="white-text"><?php echo $message; ?>
                </span>
              </div>
            </div>
          </div>
          <?php header( "refresh:3;url=viewpackage.php" ); } ?>
          <form method="post">
            <div class="col s12 m6 offset-m3<?php if(isset($_POST['delete'])){ echo " hide"; } ?>">
              <ul class="collection">

                <?php 

                $query="SELECT * FROM packages";
                $results = $link->query($query);

                while ($row = mysqli_fetch_array($results)) { ?>
                <li class="collection-item"><p>
                  <input class="left" type="checkbox" name="checkbox[]" value="<?php echo $row['id']?>" id="<?php echo $row['id']?>" />
                  <label for="<?php echo $row['id']?>"><?php echo $row['name']; ?></label></li>


                  <?php } ?>
                </ul>
                <button class="btn waves-effect waves-light<?php if(isset($_POST['delete'])){ echo " hide"; } ?>" type="submit" name="delete">Delete Packages
                  <i class="material-icons right">not_interested</i>
                </button>
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