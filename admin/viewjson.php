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

if (!file_exists("../packages.json")) {
  $error = "Packages.JSON not found, have you created it yet?";
} else {

  $json = file_get_contents("../packages.json");
  $data = json_decode($json);
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
  <title>View Packages.JSON</title>
</head>

<body>
  <?php include('header.php'); ?>     
  <main>
    <br>
    <div class="container">
      <div class="row">
        <div class="col s12 m10 offset-m1 center-align">
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
        </div>
      </div>
    </div>
    <?php if (isset($data)){ ?>
    <table class="responsive-table striped bordered">
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Author</th>
        <th>Category</th>
        <th>Type</th>
        <th>Version</th>
        <th>Website</th>
        <th>Download Path</th>
        <th>Info Path</th>        
      </tr>
      <?php foreach ($data->packages as $package){?>
      <tr>
        <td><?php echo $package->{name}; ?></td>
        <td><?php echo $package->{'short_description'}; ?></td>
        <td><?php echo $package->{'author'}; ?></td>
        <td><?php echo $package->{'category'}; ?></td>
        <td><?php echo $package->{'type'}; ?></td>
        <td><?php echo $package->{'version'}; ?></td>
        <td><?php echo $package->{'website'}; ?></td>
        <td><?php echo $package->{'dl_path'}; ?></td>
        <td><?php echo $package->{'info_path'}; ?></td>
      </tr>
      <?php } ?>
    </table>
    <?php } ?>
  </main>
<?php include("footer.php");?>



  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
  <script src="custom.js"></script>
</body>
</html>