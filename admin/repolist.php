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
if (!file_exists("../repo.list")) {
  $error = "Repo.list not found, have you created it yet?";
} else {

  $json = file_get_contents("../repo.list");
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
  <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />
  <title>Manage Repo List</title>
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
        <div class="col s12">
          <ul class="tabs">
            <li class="tab col s3"><a class="text-white" href="#repodb">Database</a></li>
            <li class="tab col s3"><a class="text-white" href="#list">repo.list</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div id="repodb">
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
   </div>
   <div id="list">
     <?php if (isset($data)){ ?>
     <table class="responsive-table striped bordered">
       <tr>
         <th>Name</th>
         <th>URL</th>        
       </tr>
       <?php foreach ($data->repos as $repodeets){?>
       <tr>
         <td><?php echo $repodeets->{'name'}; ?></td>
         <td><?php echo $repodeets->{'url'}; ?></td>
       </tr>
       <?php } ?>
     </table>
     <?php } ?>
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