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
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />
  <title>Add Custom Package</title>
</head>

<body>
<?php  if ($_SESSION['devtype'] == TRUE){
  include('3dshead.php'); 
} else {

  include('header.php'); } ?>     
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
            <div class="input-field col s12 m6">
              <input required name="name" type="text">
              <label for="name">*Package Name</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="desc" type="text">
              <label for="desc">*Package Description</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="author" type="text">
              <label for="author">*Package Author</label>
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
              <input name="website" type="text">
              <label for="website">Website</label>
            </div>
            <div class="input-field col s12 m6">
              <input name="version" type="text">
              <label for="version">Package Version</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="dl_path" type="text">
              <label class="tooltipped" for="dl_path" data-position="top" data-delay="50" data-tooltip="Usually 3ds/packagename">*Download Path <i class="tiny material-icons">info_outline</i></label>
            </div>
            <div class="input-field col s12 m6">
              <input name="info_path" type="text">
              <label class="tooltipped" for="info_path" data-position="top" data-delay="50" data-tooltip="Usually 3ds/packagename/packagefile.smdh">SMDH Path <i class="tiny material-icons">info_outline</i></label>
            </div>
            <button class="btn btn-white waves-effect waves-light" type="submit" name="addpackage">Add Package
              <i class="material-icons right">send</i>
            </button>
            <br>
            <p>Fields marked with * are mandatory</p>
          </form>
        </div>
      </div>
    </div>
  </main>
<?php include("footer.php");?>



  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
  <script src="custom.js"></script>
</body>
</html>