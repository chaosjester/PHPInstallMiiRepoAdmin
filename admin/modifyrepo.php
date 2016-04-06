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

if(isset($_POST['modifyrepo'])) {


  $id = mysqli_real_escape_string($link, $packageid);
  $name = mysqli_real_escape_string($link, $_POST['name']);
  $url = mysqli_real_escape_string($link, $_POST['url']);
 

  $query = "UPDATE repos SET name='$name', url='$url'
  WHERE `id`='$id'";

$link->query($query);

if(mysql_errno()){
    $error =  "MySQL error ".mysql_errno().": "
         .mysql_error()."\n<br>When executing <br>\n$query\n<br>";
} else {

  $message = "Repo Modified successfully<br>Redirecting back to Repo list in 3 seconds";
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
  <title>Modigy Repo List</title>
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
        <div class="col s12 m12 center-align">

          <?php if(isset($_POST['modifyrepo'])){ ?>
          <div class="row">
          <div class="col s12 m6 offset-m3">
              <div class="card-panel green">
                <span class="white-text"><?php echo $message; ?>
                </span>
              </div>
            </div>
          </div>
          <?php header( "refresh:3;url=repolist.php" ); } ?>
          <form method="post">
        <?php 
        

              $query="SELECT * FROM repos WHERE `id`='$packageid'";
              $results = $link->query($query);

              while ($row = mysqli_fetch_array($results)) { ?>

              <div class="input-field col s12 m6">
                <input name="name" type="text" value="<?php echo $row['name']; ?>" onfocus="if(this.value=='<?php echo $row['name']; ?>') this.value='<?php echo $row['name']; ?>';">
                <label for="name">Repo Name</label>
              </div>
              <div class="input-field col s12 m6">
                <input name="url" type="text" value="<?php echo $row['url']; ?>" onfocus="if(this.value=='<?php echo $row['url']; ?>') this.value='<?php echo $row['url']; ?>';">
                <label for="url">Repo URL</label>
              </div>
              <button class="btn waves-effect waves-light" type="submit" name="modifyrepo">Save Changes
                <i class="material-icons left">done</i>
              </button>
              <?php } ?>
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