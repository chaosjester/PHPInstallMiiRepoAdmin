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

if(isset($_POST['addrepo'])) {

  $name = mysqli_real_escape_string($link, $_POST['name']);
  $url = mysqli_real_escape_string($link, $_POST['url']);


  $query = "INSERT INTO repos (`name`, `url`)
  VALUES ('$name','$url')";

 $link->query($query);

if(mysql_errno()){
    $error =  "MySQL error ".mysql_errno().": "
         .mysql_error()."\n<br>When executing <br>\n$query\n<br>";
} else {

  $message = "Package added successfully<br>Redirecting to Repo List in 3 seconds";
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
  <title>Add Repo</title>
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
        <h3>Add Repo</h3>
          <?php if(isset($message)) { ?>
          <div class="card-panel col s6 m10 offset-m1 green white-text">
            <p><?php echo $message; ?></p>
          </div>
          <?php header( "refresh:3;url=repolist.php" ); } ?>
          <?php if(isset($error)) { ?>
          <div class="card-panel col s6 m10 offset-m1 red white-text">
            <p><?php echo $error; ?></p>
          </div>
          <?php } ?>
          <form method="post">
            <div class="input-field col s12 m6">
              <input required name="name" type="text">
              <label for="name">Repo Name</label>
            </div>
            <div class="input-field col s12 m6">
              <input required name="url" type="text">
              <label for="url">Repo URL</label>
            </div>
           <button class="btn waves-effect waves-light" type="submit" name="addrepo">Add Repo
              <i class="material-icons right">save</i>
            </button>
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