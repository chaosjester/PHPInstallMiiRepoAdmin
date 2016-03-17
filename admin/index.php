<?php
require ("../reposettings.php");
include ("includes/connection.php");

// if(isset($_POST['login'])){

//   $email = mysqli_real_escape_string($link, $_POST['email']);
//   $password = md5(md5($_POST['email'].$_POST['password']));

//  $query="SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";

//  if(mysqli_num_rows($query)==1) {
//  header("Location:admin.php");
//   exit();




  if(isset($_SESSION['name'])!="")
  {
   header("Location: index.php");
  }
  if(isset($_POST['login']))
  {
   $email = mysqli_real_escape_string($_POST['email']);
   $password = mysqli_real_escape_string(md5(md5($_POST['email'].$_POST['password'])));
   $query = "SELECT * FROM users WHERE email='$email'";
   $row= $link->$query;
   if($row['password']== $password)
   {
    $_SESSION['name'] = $row['name'];
    header("Location: admin.php");
   }
   else
   {
    ?>
          <script>alert('wrong details');</script>
          <?php
   }
   
  }


// } else {
//   header("Location:index.php?err=".urlencode("This account does not exist"));
//   exit();
// }



?>

<!DOCTYPE html>
<html>
<head>
  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="../custom.css">
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
</head>

<body>
  <header>
    <nav>
      <div class="nav-wrapper blue-grey darken-4">
        <a class="brand-logo center"><?php echo $reponame ?></a>
        <ul class="right hide-on-med-and-down">
          <li><a href="../admin/register.php"><i class="material-icons right">perm_identity</i>Register</a></li>
          <li><a href="../admin/"><i class="material-icons right">view_module</i>Admin Login</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <main>
    <div class="container">
      <div class="row">
        <div class="col s12 m6 offset-m3 center-align">
          <h3>Admin Log in</h3>
          <hr>
          <?php if(isset($_GET['err'])){ ?>
          <div class="row">
            <div class="col s12 center-align">
            <div class="card-panel dismissable red accent-4 white-text">
            <?php echo $_GET['err'] ?>
            </div>
            </div>
          </div>
          <?php }?>
          <?php if(isset($_GET['success'])){ ?>
          <div class="row">
            <div class="col s12 center-align">
            <div class="card-panel dismissable green white-text">
            <?php echo $_GET['success'] ?>
            </div>
            </div>
          </div>
          <?php }?>
          <form method="post">
            <div class="row">
              <div class="input-field col s12">
                <input type="email" class="validate">
                <label for="email">Email</label>
              </div>
              <div class="input-field col s12">
                <input type="password" class="validate">
                <label for="password">Password</label>
              </div>
            </div>
            <div class="row">
              <div class="col s12 input-field left-align">
                <button class="btn waves-effect waves-light" type="submit" name="login">Log In
                  <i class="material-icons right">send</i>
                </button>            
                <input type="checkbox" id="remember_me" />
                <label for="remember_me">Remember me?</label>            
              </div>
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
        Created with PHP InstallMii Repo creator.
        <a class="grey-text text-lighten-4 right" href="https://github.com/chaosjester/PHPInstallMiiRepo" target="_blank">Project GitHub page</a>
      </div>
    </div>
  </footer>



  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>
</html>