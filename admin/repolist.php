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

if(isset($_POST['createlist'])){

    $query = "SELECT `name`, `url` FROM `repos` WHERE 1";
    $result = $link->query($query);
    $temp = array();
    while($row = $result->fetch_assoc()) {
      $temp[] = $row;
    }
    $json = json_encode(array("repos"=>$temp), JSON_UNESCAPED_SLASHES);
    file_put_contents('../repo.list', $json);

    if(file_exists("../repo.list")) {

    $message = "repo.list File created";
    } else {
      $error = "repo.list not created, check your webserver logs for erros";
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
  <title>Manage Repo List</title>
</head>

<body>
<?php include('header.php'); ?>    
  <main>
    <br>


    <table class="responsive-table striped bordered">
     <tr>
       <th></th>
       <th>Name</th>
       <th>URL</th>
     </tr>
     <?php 

     $query="SELECT * FROM repos";
     $results = $link->query($query);

     while ($row = mysqli_fetch_array($results)) { ?>
     <tr>
       <td>
         <a href="modifyrepo.php?id=<?php echo $row['id']; ?>" class="waves-effect waves-light btn">Modify</a>
       </td>
       <td><?php echo $row['name']; ?></td>
       <td><?php echo $row['url']; ?></td>
     </tr>
     <?php } ?>
   </table>
   <br><br>
   <div class="container">
     <div class="row">
       <div class="col s12 m6 offset-m3 center align">
         <form method="post">
           <button class="waves-effect waves-light btn" name="createlist" type="submit">Create repo.list</button>
         </form>
       </div>
     </div>
     <?php if(isset($message)){ ?>
     <div class="row">
       <div class="col s12 m10 offset-m1 center-align">
         <div class="card-panel green">
           <span class="white-text"><?php echo $message; ?>
           </span>
         </div>
       </div>
     </div>
     <?php ;} ?>
     <?php if(isset($error)){ ?>
     <div class="row">
       <div class="col s12 m10 offset-m1 center-align">
         <div class="card-panel red">
           <span class="white-text"><?php echo $error; ?>
           </span>
         </div>
       </div>
     </div>
     <?php ;} ?>
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