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

// Calculate the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Query to select orders in 'waiting_payment' status
$sql = "SELECT order_id, orderDateTime FROM order_member WHERE orderStatus = 'waiting_payment'";
$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $order_id = $row['order_id'];
        $orderDateTime = $row['orderDateTime'];

        // Calculate the date one day from the orderDateTime
        $oneDayAfterOrder = date('Y-m-d H:i:s', strtotime($orderDateTime . ' +1 day'));

        // Check if the current date and time is greater than or equal to one day after the orderDateTime
        if ($currentDateTime >= $oneDayAfterOrder) {
            // Update the order status to 'cancel'
            $updateSql = "UPDATE order_member SET orderStatus = 'cancel' WHERE order_id = $order_id";
            mysqli_query($con, $updateSql);
        }
    }
}

mysqli_close($con);
?>
