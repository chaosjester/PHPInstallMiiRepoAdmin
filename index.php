<?php
if (!file_exists("reposettings.php")){
	header('Location:./admin/install');
} else {
	require ("reposettings.php");
}
if (file_exists("./admin/includes/connection.php")) {
	include("./admin/includes/connection.php");
	$query="SELECT * FROM packages";
	$results = $link->query($query);
}


?><!DOCTYPE html>
<html>
<head>
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<!--Import materialize.css-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
	<link rel="stylesheet" type="text/css" href="custom.css">
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />

</head>

<body>
	<header>

	<nav>
	  <div class="nav-wrapper blue-grey darken-4">
	    <a href="#!" class="brand-logo center"><?php echo $reponame ?></a>
	    <a href="#" data-activates="sidemenu" class="button-collapse"><i class="material-icons">menu</i></a>
	    <ul class="right hide-on-med-and-down">
	    <li><a href="admin/"><i class="material-icons right">view_module</i>Admin Login</a></li>
	    </ul>
	    <ul class="side-nav" id="sidemenu">
	    <li><a href="admin/"><i class="material-icons right">view_module</i>Admin Login</a></li>
	    </ul>
	  </div>
	</nav>
	        







	<!-- 	<nav>
			<div class="nav-wrapper blue-grey darken-4">
				<a class="brand-logo center"><?php echo $reponame ?></a>
				<ul class="right hide-on-med-and-down">
					<li><a href="admin/"><i class="material-icons right">view_module</i>Admin Login</a></li>
				</ul>
				<ul id="slide-out" class="side-nav fixed hide-on-large-only">
					<li><a href="admin/"><i class="material-icons right">view_module</i>Admin Login</a></li>
				</ul>
				<a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
			</div>
		</nav> -->
	</header>
	<main>
		<div class="parallax-container">
			<div class="parallax"><img src="./bg1.png"></div>
		</div>
		<div class="container">
			<div class="row center-align">
				<h4>Welcome to <?php echo $reponame ?></h4>				
				<p>Please download my repo.list and place on your 3DS' SD card in your InstallMii directory.
					<br><br>
					<a href="repo.list" class="waves-effect waves-teal" download><i class="fa fa-download fa-2x"></i><br>Download repo.list </a></p>
				</div>
				<div class="row center-align<?php if(!isset($results)){ echo " hide"; } ?>">
					<div class="col s12 m6 offset-m3">
						<a class="waves-effect waves-light btn modal-trigger center-align" href="#packages">View Packages on repo</a>
						<div id="packages" class="modal modal-fixed-footer">
						   <div class="modal-content">

						<?php
						while ($row = mysqli_fetch_array($results)) { ?>
						<p><?php echo $row['name']; ?> by <?php echo $row['author']; ?></p>
						<?php } ?>
						</div>
						   <div class="modal-footer">
						     <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
						   </div>
						 </div>
					</div>
				</div>
			</div>
			<div class="parallax-container">
				<div class="parallax"><img src="./bg2.png"></div>
			</div>
		</main>
		<footer class="page-footer blue-grey darken-3" style="margin-top: -10px;">
			<div class="container">
				<div class="row">
					<div class="col l6 s12">
						<h5 class="white-text">Repo Provided by <?php echo $repoowner ?></h5>
						<p class="grey-text text-lighten-4"><?php echo $repoblurb ?></p>
					</div>

					<div class="col l6 s12">
						<h5 class="grey-text text-lighten-3">PHPInstallMiiRepo by ChaosJester and LiquidFenrir</h5>
						<p><a class="grey-text text-lighten-3" href="https://www.flickr.com/photos/axor/6886961313/" target="_blank"><i class="fa fa-flickr fa-2x left"></i> Background picture by Axel Pfaender, used under creative commons license</a></p>
						<p><a class="grey-text text-lighten-4" href="https://github.com/chaosjester/PHPInstallMiiRepoAdmin" target="_blank"><i class="fa fa-github fa-2x left"></i> Project GitHub page</a></p>

					</div>
				</div>
			</div>
		</footer>
		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<!-- Compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
		<script type="text/javascript" src="custom.js"></script>
	</body>
	</html>