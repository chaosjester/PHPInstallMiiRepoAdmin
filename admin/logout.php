<?php
session_start();

if(!isset($_SESSION['name']))
{
 header("Location: index.php");
}
else if(isset($_SESSION['name'])!="")
{
 header("Location: admin.php");
}

if(isset($_GET['logout']))
{
 session_destroy();
 session_unset();
 unset($_SESSION['name']);
 header("Location: index.php");
}
?>