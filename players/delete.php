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

    // Retrieve the image file name before deleting the row
    $sql = "SELECT image FROM Players WHERE players_id = $id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $image = $row['image'];
    unlink($image);

    // Delete the row
    $sql = "DELETE FROM Players WHERE players_id = $id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header('location:players.php');
    } else {
        echo mysqli_error($con);
    }
}
?>
