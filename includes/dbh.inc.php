<?php

$serverName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "web_dev_finals_project";

$connection = mysqli_connect($serverName, $databaseUsername, $databasePassword, $databaseName);

// uxladi>{ko0YYEUt

if (!$connection){
	die("Connection failed: ".mysqli_connect_error());
} 
	