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
    $playerScore_id = $_POST['playerScoreId'];
    $oldPlayer = $_POST['oldPlayer'];

    // Retrieve old player's ID and update scored - 1
    $updateOldPlayerQuery = "UPDATE Players SET scored = scored - 1 WHERE players_id = '$oldPlayer'";

    // Update new player's scored + 1
    $updateNewPlayerQuery = "UPDATE Players SET scored = scored + 1 WHERE players_id = '$playerId'";

    // Update Player_Score table
    $updateQuery = "UPDATE player_score SET players_id = '$playerId' WHERE playerScore_id = '$playerScore_id'";

    // Execute queries
    if (mysqli_query($con, $updateOldPlayerQuery) && mysqli_query($con, $updateNewPlayerQuery) && mysqli_query($con, $updateQuery)) {
        // Success
        header("Location: updateMatch.php?updateid=$match_id");
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }
}


if (isset($_POST['updateOP'])) {
    $opponentPlayers = $_POST['opponentPlayers'];
    $match_id = $_POST['match_id'];
    $playerScore_idOP = $_POST['playerScoreIdOP'];

    $updateQuery = "UPDATE player_score SET opponentPlayers = '$opponentPlayers' WHERE playerScore_id = '$playerScore_idOP'";

    if (mysqli_query($con, $updateQuery)) {
        // Success
        header("Location: updateMatch.php?updateid=$match_id");
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }
}
?>