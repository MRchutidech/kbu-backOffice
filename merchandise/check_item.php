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
$item_id = $_POST['item_id'];

$sql = "SELECT COUNT(*) as count FROM order_items WHERE item_id = $item_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    echo 'exists';
} else {
    echo 'not_exists';
}
?>