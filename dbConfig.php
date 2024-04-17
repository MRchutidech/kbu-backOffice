<?php

$dbHost = "localhost";
$dbUsername = "admin_kbufc";
$dbPassword = "E1eXlHrXzS";
$dbName = "admin_kbufc";

$con = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

if(mysqli_connect_errno()){
    echo "Failed to connect";
    exit();
}

?>