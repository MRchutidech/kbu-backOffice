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
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__ . "/../../dbConfig.php");

if (isset($_POST['submit'])) {
    $playerId = $_POST['player'];
    $match_id = $_POST['match_id']; // Retrieve match_id from the hidden input field

    // Insert data into the Player_Score table
    $insertQuery = "INSERT INTO player_score (players_id, match_id) VALUES ('$playerId', '$match_id')";

    if (mysqli_query($con, $insertQuery)) {
        // Increment the 'scored' column in the Players table for the specified player
        $updateScoreQuery = "UPDATE Players SET scored = scored + 1 WHERE players_id = '$playerId'";
        if (mysqli_query($con, $updateScoreQuery)) {
            // Success
            header("Location: updateMatch.php?updateid=$match_id"); // Redirect to a success page
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


if (isset($_POST['submitOP'])) {
    $opponentPlayer = $_POST['opponentPlayers'];
    $match_id = $_POST['match_id']; // Retrieve match_id from the hidden input field

    // Insert data into the Player_Score table
    $insertQuery = "INSERT INTO player_score (opponentPlayers, match_id) VALUES ('$opponentPlayer', '$match_id')";

    if (mysqli_query($con, $insertQuery)) {
        // Success
        header("Location: updateMatch.php?updateid=$match_id"); // Redirect to a success page
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con); // Use $con instead of $connection
    }
}
?>