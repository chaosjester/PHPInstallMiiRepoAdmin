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
      $name = $list[$i][$fields[0]] = substr($file,8,80);
      $desc = $list[$i][$fields[1]] = substr($file,136,200);
      $author = $list[$i][$fields[2]] = substr($file,392,80); // no more scraping, until dev's start using the SMDH file spec properly!
      $category = $list[$i][$fields[3]] = mysqli_real_escape_string($link, "na");
      $website = $list[$i][$fields[4]] = mysqli_real_escape_string($link, "na");
      $type = $list[$i][$fields[5]] = mysqli_real_escape_string($link, "3ds"); // to make installMii install in the sd:/3ds/ folder
      $version = $list[$i][$fields[6]] = mysqli_real_escape_string($link, "na");
      $dlp = $list[$i][$fields[7]] = mysqli_real_escape_string($link, $dl_path[$i]);
      $infop = $list[$i][$fields[8]] = mysqli_real_escape_string($link, $info_path[$i]);

      $name = mysqli_real_escape_string($link, str_replace("\0", "",$name));
      $desc = mysqli_real_escape_string($link, str_replace("\0", "",$desc));
      $author = mysqli_real_escape_string($link, str_replace("\0", "",$author));
      $icon = mysqli_real_escape_string($link, $icon);

      $query = "SELECT * FROM `packages` WHERE dl_path='$dlp'";
      $result = $link->query($query);
      $row = mysqli_fetch_array($result,MYSQLI_NUM);

      if ($row[0] >= 1) {

        $warning = $warning."Package ".$name." already exists and has not been changed<br>";

      }  else { 

        $updatedb = "INSERT INTO packages (`name`, `short_description`, `author`, `category`, `website`, `type`, `version`, `dl_path`, `info_path`) VALUES ('$name','$desc','$author','$category','$website','$type','$version','$dlp','$infop')";

        $link->query($updatedb);
        $message = $message."Package ".$name." added<br>";


      } 

    }

    $query = "SELECT * FROM packages WHERE 1";
    $result = $link->query($query);


    while($row = $result->fetch_assoc()) {

      $dlpath = $row['dl_path'];

      if (!file_exists('../'.$dlpath)) {

        $query = "DELETE FROM packages WHERE dl_path='$dlpath'";

        $link->query($query);

        $deleted =  $deleted."Package ".$row['name']." Deleted<br>";

      }
    }
    if (isset($message)){

      $message = $message."<br>If the package name is blank, there is no SMDH file present or it did not contain any information.<br>A Dummy entry has been created.<br>Check the View Packages page for more details";
    }
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="custom.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Generate Package Lists</title>
  </head>

  <body>
    <?php include('header.php'); ?>   
    <main>
      <br>
      <div class="container">
        <div class="row">
          <div class="col s12 m10 offset-m1 center-align">
            <h2>Generate Required Repo Files</h2>
            <p>This process will need to be done when any packages are updated, added or deleted from your repo</p>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col s12">
              <ul class="tabs">
                <li class="tab col s12 m6"><a class="text-white" href="#step1">Step 1</a></li>
                <li class="tab col s12 m6"><a class="text-white" href="#step2">Step 2</a></li>
                <li class="tab col s12 m6"><a class="text-white" href="#step3">Step 3</a></li>
              </ul>
            </div>
          </div>
        </div>
        <form method="post" action="generatejson.php#step1">
          <div id="step1">
            <div class="row">
              <div class="col s12 m6 offset-m3 center-align">
                <div class="card">
                  <div class="card-content black-text">
                    <span class="card-title">Step 1</span>
                    <p>Scan and create package.list files</p>
                  </div>
                  <div class="card-action">
                  <button class="btn waves-effect waves-light" type="submit" name="pacakgelist">Create package.list</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </form>
          <form method="post" action="generatejson.php#step2">
          <div id="step2">
            <div class="row">
              <div class="col s12 m6 offset-m3 center-align">
                <div class="card">
                  <div class="card-content black-text">
                    <span class="card-title">Step 2</span>
                    <p>Scan for any SMDH files and scrape information and remove any packages that have been deleted</p>
                  </div>
                  <div class="card-action">
                  <button class="btn waves-effect waves-light" type="submit" name="scansmdh">Scan SMDH Files</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </form>
          <form method="post" action="generatejson.php#step3">
          <div id="step3">
            <div class="row">
              <div class="col s12 m6 offset-m3 center-align">
                <div class="card">
                  <div class="card-content black-text">
                    <span class="card-title">Step 3</span>
                    <p>Generate Package.JSON file</p>
                  </div>
                  <div class="card-action">
                  <button class="btn waves-effect waves-light" type="submit" name="generatejson">Generate JSON</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <br>
      <div class="container">
        <?php if(isset($deleted)){ ?>
        <div class="row">
         <div class="col s12 m10 offset-m1 center-align">
           <div class="card-panel red lighten-1">
             <span class="white-text"><?php echo $deleted; ?>
             </span>
           </div>
         </div>
       </div>
       <?php ;} ?>
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
       <?php if(isset($warning)){ ?>
       <div class="row">
        <div class="col s12 m10 offset-m1 center-align">
          <div class="card-panel orange">
            <span class="white-text"><?php echo $warning; ?>
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