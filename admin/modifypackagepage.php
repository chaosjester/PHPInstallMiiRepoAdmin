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

if(isset($_GET['id'])){
  $packageid = $_GET['id'];
}

if(isset($_POST['modifypackage'])) {


  $id = mysqli_real_escape_string($link, $packageid);
  $name = mysqli_real_escape_string($link, $_POST['name']);
  $desc = mysqli_real_escape_string($link, $_POST['desc']);
  $author = mysqli_real_escape_string($link, $_POST['author']);
  $category = mysqli_real_escape_string($link, $_POST['category']);
  $website = mysqli_real_escape_string($link, $_POST['website']);
  $type = mysqli_real_escape_string($link, "3ds"); // This always needs to be 3ds for InstallMii to install it correctly
  $version = mysqli_real_escape_string($link, $_POST['version']);
  $dl_path = mysqli_real_escape_string($link, $_POST['dl_path']);
  $info_path = mysqli_real_escape_string($link, $_POST['info_path']);

  $query = "UPDATE packages SET name='$name', short_description='$desc', author='$author', category='$category', website='$website', type='$type', version='$version', dl_path='$dl_path', info_path='$info_path'
  WHERE `id`='$id'";

if ($link->query($query) === TRUE) {
    $message = "Package Modified successfully<br>Redirecting back to package list in 3 seconds";
  $link->close();
} else {
  $error = 'Error updating package<br>'. $link->error.'<br><br>';
  
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
  <link rel="stylesheet" type="text/css" href="custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Delete Packages</title>
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
          <li class="bold"><a href="admin.php" class="waves-effect waves-teal">Home</a></li>
          <li class="no-padding">
           <ul class="collapsible collapsible-accordion">
             <li>
               <a class="collapsible-header waves-effect waves-teal">Packages</a>
               <div class="collapsible-body">
                 <ul>
                  <li><a href="viewpackage.php">View Packages</a></li>
                  <li><a href="addcustom.php">Add Custom Package</a></li>
                  <li><a href="deletepackage.php">Delete Packages</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </li>
        <li class="no-padding">
         <ul class="collapsible collapsible-accordion">
           <li>
             <a class="collapsible-header waves-effect waves-teal">Repo Settings</a>
             <div class="collapsible-body">
               <ul>
                <li><a href="repolist.php">Manage Repo List</a></li>
                <li><a href="addrepo.php">Add Repo</a></li>
                <li><a href="deleterepo.php">Delete Repo</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </li>
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

          <?php if(isset($_POST['modifypackage'])){ ?>
          <div class="row">
          <div class="col s12 m6 offset-m3">
              <div class="card-panel green">
                <span class="white-text"><?php echo $message; ?>
                </span>
              </div>
            </div>
          </div>
          <?php header( "refresh:3;url=viewpackage.php" ); } ?>
          <?php if(isset($error)){ ?>
          <div class="row">
          <div class="col s12 m6 offset-m3">
              <div class="card-panel red">
                <span class="white-text"><?php echo $error; ?>
                </span>
              </div>
            </div>
          </div>
          <?php header( "refresh:3;url=viewpackage.php" ); } ?>
          <form method="post">
        <?php 
        

              $query="SELECT * FROM packages WHERE `id`='$packageid'";
              $results = $link->query($query);

              while ($row = mysqli_fetch_array($results)) { ?>

              <div class="input-field col s12 m6">
                <input name="name" type="text" value="<?php echo $row['name']; ?>" onfocus="if(this.value=='<?php echo $row['name']; ?>') this.value='<?php echo $row['name']; ?>';">
                <label for="name">Package Name</label>
              </div>
              <div class="input-field col s12 m6">
                <input name="desc" type="text" value="<?php echo $row['short_description']; ?>" onfocus="if(this.value=='<?php echo $row['short_description']; ?>') this.value='<?php echo $row['short_description']; ?>';">
                <label for="desc">*Package Description</label>
              </div>
              <div class="input-field col s12 m6">
                <input name="author" type="text" value="<?php echo $row['author']; ?>" onfocus="if(this.value=='<?php echo $row['author']; ?>') this.value='<?php echo $row['author']; ?>';">
                <label for="author">Package Author</label>
              </div>
              <div class="input-field col s12 m6">
                <select name="category">
                  <option value="Games">Games</option>
                  <option value="Application">Applications</option>
                  <option value="Tools">Tools</option>
                </select>
                <label for="category">Package Category</label>
              </div>
              <div class="input-field col s12 m6">
                <input name="website" type="text" value="<?php echo $row['website']; ?>" onfocus="if(this.value=='<?php echo $row['website']; ?>') this.value='<?php echo $row['website']; ?>';">
                <label for="website">Website</label>
              </div>
              <div class="input-field col s12 m6">
                <input name="version" type="text" value="<?php echo $row['version']; ?>" onfocus="if(this.value=='<?php echo $row['version']; ?>') this.value='<?php echo $row['version']; ?>';">
                <label for="version">Package Version</label>
              </div>
              <div class="input-field col s12 m6">
                <input name="dl_path" type="text" value="<?php echo $row['dl_path']; ?>" onfocus="if(this.value=='<?php echo $row['dl_path']; ?>') this.value='<?php echo $row['dl_path']; ?>';">
                <label class="tooltipped" for="dl_path" data-position="top" data-delay="50" data-tooltip="Usually 3ds/packagename/">Download Path <i class="tiny material-icons">info_outline</i></label>
              </div>
              <div class="input-field col s12 m6">
                <input name="info_path" type="text" value="<?php echo $row['info_path']; ?>" onfocus="if(this.value=='<?php echo $row['info_path']; ?>') this.value='<?php echo $row['info_path']; ?>';">
                <label class="tooltipped" for="info_path" data-position="top" data-delay="50" data-tooltip="Usually 3ds/packagename/packagefile.smdh">SMDH Path <i class="tiny material-icons">info_outline</i></label>
              </div>
              <button class="btn waves-effect waves-light" type="submit" name="modifypackage">Save Changes
                <i class="material-icons left">done</i>
              </button>
              <?php } ?>
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