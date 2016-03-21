<?php
session_start();

require ("../reposettings.php");
include ("includes/connection.php");

if(!isset($_SESSION['name']))
{
 header("Location: index.php?err=".urlencode("Either you are not logged in or your username and/or password are incorrect. Please try again."));
 exit();
}

if(isset($_POST['addpackage'])) {

  $name = mysqli_real_escape_string($link, $_POST['name']);
  $desc = mysqli_real_escape_string($link, $_POST['desc']);
  $author = mysqli_real_escape_string($link, $_POST['author']);
  $category = mysqli_real_escape_string($link, $_POST['category']);
  $website = mysqli_real_escape_string($link, $_POST['website']);
  $type = mysqli_real_escape_string($link, "3ds");
  $version = mysqli_real_escape_string($link, $_POST['version']);
  $dl_path = mysqli_real_escape_string($link, $_POST['dl_path']);
  $info_path = mysqli_real_escape_string($link, $_POST['info_path']);

  $query = "INSERT INTO packages (`name`, `short_description`, `author`, `category`, `website`, `type`, `version`, `dl_path`, `info_path`)
  VALUES ('$name','$desc','$author','$category','$website','$type','$version','$dl_path','$info_path')";

 $link->query($query);

if(mysql_errno()){
    $error =  "MySQL error ".mysql_errno().": "
         .mysql_error()."\n<br>When executing <br>\n$query\n<br>";
} else {

  $message = "Package added successfully";
  $link->close();
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
  <title>Add Custom Package</title>
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
          <li><a href="viewpackage.php">View Packages</a></li>
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
        <div class="col s12 m10 offset-m1 center-align">
        <h3>Add Custom Package</h3>
          <?php if(isset($message)) { ?>
          <div class="card-panel col s6 m10 offset-m1 green white-text">
            <p><?php echo $message; ?></p>
          </div>
          <?php } ?>
          <?php if(isset($error)) { ?>
          <div class="card-panel col s6 m10 offset-m1 red white-text">
            <p><?php echo $error; ?></p>
          </div>
          <?php } ?>
          <form method="post">
            <div class="input-field col s6">
              <input required name="name" type="text">
              <label for="name">*Package Name</label>
            </div>
            <div class="input-field col s6">
              <input required name="desc" type="text">
              <label for="desc">*Package Description</label>
            </div>
            <div class="input-field col s6">
              <input required name="author" type="text">
              <label for="author">*Package Author</label>
            </div>
            <div class="input-field col s6">
              <select name="category">
                <option value="Games">Games</option>
                <option value="Application">Applications</option>
                <option value="Tools">Tools</option>
              </select>
              <label for="category">Package Category</label>
            </div>
            <div class="input-field col s6">
              <input name="website" type="text">
              <label for="website">Website</label>
            </div>
            <div class="input-field col s6">
              <input name="version" type="text">
              <label for="version">Package Version</label>
            </div>
            <div class="input-field col s6">
              <input required name="dl_path" type="text">
              <label class="tooltipped" for="dl_path" data-position="top" data-delay="50" data-tooltip="Usually 3ds/packagename">*Download Path <i class="tiny material-icons">info_outline</i></label>
            </div>
            <div class="input-field col s6">
              <input required name="info_path" type="text">
              <label class="tooltipped" for="info_path" data-position="top" data-delay="50" data-tooltip="Usually 3ds/packagename/packagefile.smdh">*SMDH Path <i class="tiny material-icons">info_outline</i></label>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="addpackage">Add Package
              <i class="material-icons right">send</i>
            </button>
            <br>
            <p>Fields marked with * are mandatory</p>
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
  <script src="custom.js"></script>
</body>
</html>