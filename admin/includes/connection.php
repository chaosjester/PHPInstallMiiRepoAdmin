<?php 
// SQL Database settings
// Enter server address, user, password and database name

$server = "localhost";
$dbuser = "3dsrepo";
$dbpass = "3dsrepo";
$dbname = "3dsrepo";

// Stop editing

$link = new mysqli($server, $dbuser, $dbpass, $dbname);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

?>
