<?php
// Database connection parameters
$serverhost = "localhost";
$databaseuser = "root";
$databasepassword = "";
$databasename = "tutoring_management";

if(!$connection = mysqli_connect($serverhost, $databaseuser, $databasepassword, $databasename)){
    die("Connection failed!!!");
}

