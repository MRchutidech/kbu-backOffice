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
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../dbConfig.php");
if (!$con) {
    die(mysqli_error($con));
}

if (isset($_GET['updateid'])) {
    $id = $_GET['updateid'];
    $sql = "UPDATE order_member SET orderStatus = 'preparing' WHERE order_id  = '$id'";
    if (mysqli_query($con, $sql)) {
        // Success
        header("Location: updateOrder.php?updateid=$id");
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }


}
?>
