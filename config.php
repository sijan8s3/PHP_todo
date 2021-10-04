<?php


//connect to the database

$servername = "localhost";
$username = "root";
$password = "";
$database = "todoNotes";
$site_url="http://localhost/PHP_todo/";

//connection
$conn = mysqli_connect($servername, $username, $password, $database);


//checking connection
if (!$conn) {
    die("Unable to connect to Database. Error: " . mysqli_connect_error());
}

?>
