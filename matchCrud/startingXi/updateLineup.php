<?php
session_start();

// Check if the user is authenticated; if not, redirect to the login page
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../../login.php");
    exit();
}
session_regenerate_id(true);
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
require_once(__DIR__ . "/../../dbConfig.php");

if (isset($_POST['update'])) {
    $playerId = $_POST['player'];
    $match_id = $_POST['match_id'];
    $startXi_id = $_POST['startXi_id'];
    $oldPlayer = $_POST['oldPlayer'];

    // Retrieve old player's ID and update scored - 1
    $updateOldPlayerQuery = "UPDATE Players SET appearances = appearances - 1 WHERE players_id = '$oldPlayer'";

    // Update new player's scored + 1
    $updateNewPlayerQuery = "UPDATE Players SET appearances = appearances + 1 WHERE players_id = '$playerId'";

    // Update Player_Score table
    $updateQuery = "UPDATE startingxi SET players_id = '$playerId' WHERE startXi_id = '$startXi_id'";

    // Execute queries
    if (mysqli_query($con, $updateOldPlayerQuery) && mysqli_query($con, $updateNewPlayerQuery) && mysqli_query($con, $updateQuery)) {
        // Success
        header("Location: updateXi.php?updateid=$match_id");
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }
}

if (isset($_POST['updateSub'])) {
    $playerId = $_POST['player'];
    $match_id = $_POST['match_id'];
    $startXi_id = $_POST['subXi_id'];
    $oldPlayer = $_POST['oldSub'];

    // Retrieve old player's ID and update scored - 1
    $updateOldPlayerQuery = "UPDATE Players SET appearances = appearances - 1 WHERE players_id = '$oldPlayer'";

    // Update new player's scored + 1
    $updateNewPlayerQuery = "UPDATE Players SET appearances = appearances + 1 WHERE players_id = '$playerId'";

    // Update Player_Score table
    $updateQuery = "UPDATE startingxi SET players_id = '$playerId' WHERE startXi_id = '$startXi_id'";

    // Execute queries
    if (mysqli_query($con, $updateOldPlayerQuery) && mysqli_query($con, $updateNewPlayerQuery) && mysqli_query($con, $updateQuery)) {
        // Success
        header("Location: updateXi.php?updateid=$match_id");
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }
}

?>