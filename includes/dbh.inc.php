<?php

$host = $_SERVER['HTTP_HOST'];

if (str_contains($host, "dcism.org")) {
	$serverName = "localhost";
	$databaseUsername = "s21103565_web_dev_finals_project";
	$databasePassword = "andre510";
	$databaseName = "s21103565_web_dev_finals_project";
} else {
	$serverName = "localhost";
	$databaseUsername = "root";
	$databasePassword = "";
	$databaseName = "web_dev_finals_project";
}

$connection = mysqli_connect($serverName, $databaseUsername, $databasePassword, $databaseName);

	
if (!$connection){
	echo "Error: Database connection Failed";
	exit("Connection failed: ".mysqli_connect_error());
} 