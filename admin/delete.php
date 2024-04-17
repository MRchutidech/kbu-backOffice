<?php
session_start();

// Check if the user is authenticated; if not, redirect to the login page
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login.php");
    exit();
}
session_regenerate_id(true);
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
require_once("../dbConfig.php");

if (!$con) {
    die(mysqli_error($con));
}

if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    // Delete the row
    $sql = "DELETE FROM admin WHERE id = $id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header('location:../index.php');
    } else {
        echo mysqli_error($con);
    }
}
?>

