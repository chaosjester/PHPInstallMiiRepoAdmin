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

if(isset($_POST['pacakgelist'])) {
  $apps = array_diff(scandir('../3ds/'), array('..', '.'));
  sort($apps);
  $dl_path = $apps;
  foreach ($dl_path as &$item) { $item = "3ds/".$item."/"; }
// The 2 following functions were posted by frasq@frasq.org here: http://php.net/manual/en/function.readdir.php#100710
  function listdir($dir='..') {
    $files = array();
    listdiraux($dir, $files);
    return $files;
  }

  function listdiraux($dir, &$files) {
    $handle = opendir($dir);
    while ($file = readdir($handle)) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      $filepath = $dir == '.' ? $file : $dir . '/' . $file;
      if (is_link($filepath))
        continue;
      if (is_file($filepath))
        if(strpos($filepath,"package.list") === false) {
          $files[] = $filepath;
        } else {
          continue;
        }
        else if (is_dir($filepath))
          listdiraux($filepath, $files);
      }
      closedir($handle);
    }

    $i=0;
    for($i;$i < sizeof($apps);$i++) {
      $files = listdir(substr("../".$dl_path[$i],0,-1));
      sort($files, SORT_LOCALE_STRING);
      file_put_contents("../".$dl_path[$i]."package.list",str_replace("../".$dl_path[$i],"",implode("\n", $files)));
    }
    $message = "Package.list files generated";

  }

  if(isset($_POST['scansmdh'])){
    $apps = array_diff(scandir('../3ds/'), array('..', '.'));
    sort($apps);
    $info_path = $apps;
    $dl_path = $apps;
    foreach ($dl_path as &$item) { $item = "3ds/".$item."/"; }
    foreach ($info_path as &$item) { $item = "3ds/".$item."/".$item.".smdh"; }

    $fields = array(); // dont comment that one out, will break everything else
    $fields[] = "name";
    $fields[] = "short_description";
    $fields[] = "author";
    $fields[] = "category";
    $fields[] = "website";
    $fields[] = "type";
    $fields[] = "version";
    $fields[] = "dl_path";
    $fields[] = "info_path";


    /* COMMENTED THIS OUT AS IT CAUSED ISSUES WITH DIRECTORIES THAT DO HAVE THE SMDH
    THE SIDE EFFECT IS THAT IT WILL ENTER ANY FOLDERS WITHOUT THEM, THOUGH THIS IS A BIT OF A BONUS AS THINGS MISSING SMDH STILL GET ADDED
    THEY JUST NEED SOME MANUAL CHANGES */

    // $i = 0;
    // for($i;$i < sizeof($apps);$i++) { // removing entries from the array if they don't have a .smdh in their folder or are files
    //   if(!is_dir(substr($dl_path[$i],0,-1)) || !file_exists($info_path[$i])) {
    //  unset($apps[$i]);
    //  unset($info_path[$i]);
    //  unset($dl_path[$i]);
    //   }
    // }

    sort($apps);
    sort($dl_path);
    sort($info_path);

    $list = array();
    $i = 0;
    for ($i;$i <= sizeof($apps)-1;$i++) { // scrape the SMDH files
      $list[$i] = array();
      $file = file_get_contents("../".$info_path[$i]);
      $name = $list[$i][$fields[0]] = substr($file,8,80); //reading the parts with the name, desc and author in the smdh
      $desc = $list[$i][$fields[1]] = substr($file,136,200);
      $author = $list[$i][$fields[2]] = substr($file,392,80);
      // those null parts are where the master.list idea can be better than using php to read the smdh, but I don't think there's a zone for version in a smdh
      $category = $list[$i][$fields[3]] = "na";
      $website = $list[$i][$fields[4]] = "na";
      $type = $list[$i][$fields[5]] = "3ds"; // to make installMii install in the sd:/3ds/ folder
      $version = $list[$i][$fields[6]] = "na";
      $dlp = $list[$i][$fields[7]] = $dl_path[$i];
      $infop = $list[$i][$fields[8]] = $info_path[$i];

      $name = str_replace("\0", "",$name);
      $desc = str_replace("\0", "",$desc);
      $author = str_replace("\0", "",$author);
      // $dlp = rtrim($dlp, "/");

      $query = "SELECT * FROM `packages` WHERE dl_path='$dlp'";
      $result = $link->query($query);
      $row = mysqli_fetch_array($result,MYSQLI_NUM);

      if ($row[0] >= 1) {

       $message = $message."Package ".$name." already exists and has not been changed<br>";

     }  else { 

      $updatedb = "INSERT INTO packages (`name`, `short_description`, `author`, `category`, `website`, `type`, `version`, `dl_path`, `info_path`) VALUES ('$name','$desc','$author','$category','$website','$type','$version','$dlp','$infop')";

      $link->query($updatedb);
      $message = $message."Package ".$name." added<br>";


    } 

  }
  $message = $message."<br>If the package name is blank, there is no SMDH file present.<br>A Dummy entry has been created.<br>Check the View Packages page for more details";
}
if (isset($_POST['generatejson'])){

  $repoInfo = array();
  $repoInfo["name"] = $reponame;
  $repoInfo["author"] = $repoowner;
  $repoInfo["website"] = $repourl;

  $query = "SELECT `name`, `short_description`, `author`, `category`, `website`, `type`, `version`, `dl_path`, `info_path` FROM `packages` WHERE 1";
  $result = $link->query($query);
  $temp = array();
  while($row = $result->fetch_assoc()) {
    $temp[] = $row;
  }
  $json = json_encode(array("repo"=>$repoInfo, "packages"=>$temp), JSON_UNESCAPED_SLASHES);
  file_put_contents('../packages.json', $json);

  $message = "packages.json File created";
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
  <title>Generate Package Lists</title>
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
          <li class="bold"><a href="admin.php" class="waves-effect waves-teal active">Home</a></li>
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
      <li class="active"><a href="generatejson.php">Generate Package Lists</a></li>
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
        <h2>Generate Required Repo Files</h2>
        <p>This process will need to be done when any packages are updated or added to your repo</p>
      </div>
    </div>
      <div class="row">
        <form method="post">
          <div class="col s12 m4 center-align">
            <h3>Step 1</h3>
            <p>Click the button below to scan and create the package.list file for all packages</p>
            <button class="btn waves-effect waves-light" type="submit" name="pacakgelist">Create package.list
              <i class="material-icons right">send</i>
            </button>
          </div>
          <div class="col s12 m4 center-align">
            <h3>Step 2</h3>
            <p>Click the button below to scan for any SMDH files and scrape information</p>
            <button class="btn waves-effect waves-light" type="submit" name="scansmdh">Scan SMDH Files
              <i class="material-icons right">send</i>
            </button>
          </div>
          <div class="col s12 m4 center-align">
            <h3>Step 3</h3>
            <p>Click the button below to generate the packages.json file, it is recommended to do this after Step 1 and 2</p>
            <button class="btn waves-effect waves-light" type="submit" name="generatejson">Generate packages.json
              <i class="material-icons right">send</i>
            </button>
          </div>
        </form>
      </div>
    </div>
    <br>
    <div class="container">
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