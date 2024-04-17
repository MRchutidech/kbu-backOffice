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

if (isset($_POST['submit'])) {
    $playerId = $_POST['player'];
    $match_id = $_POST['match_id']; // Retrieve match_id from the hidden input field
    $status = $_POST['status'];

    // Insert data into the Player_Score table
    $insertQuery = "INSERT INTO startingxi (players_id, match_id, status) VALUES ('$playerId', '$match_id', '$status')";

    if (mysqli_query($con, $insertQuery)) {
        // Increment the 'scored' column in the Players table for the specified player
        $updateAppearance = "UPDATE Players SET appearances = appearances + 1 WHERE players_id = '$playerId'";
        if (mysqli_query($con, $updateAppearance)) {
            // Success
            header("Location: updateXi.php?updateid=$match_id"); // Redirect to a success page
            exit();
        } else {
            // Error updating player score
            echo "Error updating player score: " . mysqli_error($con);
        }
    } else {
        // Error inserting into Player_Score table
        echo "Error inserting into Player_Score table: " . mysqli_error($con);
    }
}
?>