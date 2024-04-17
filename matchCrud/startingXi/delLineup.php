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

if (!$con) {
    die(mysqli_error($con));
}

if (isset($_GET['deleteid'], $_GET['match_id'])) {
    $deleteid = $_GET['deleteid'];
    $match_id = $_GET['match_id'];

    // Delete the row based on player type
    $table = 'startingxi'; // Assuming you are using the same table for both player types

    $sql = "SELECT players_id FROM $table WHERE startXi_id = $deleteid";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $players_id = $row['players_id'];

        $deleteQuery = "DELETE FROM $table WHERE startXi_id = $deleteid";
        $deleteResult = mysqli_query($con, $deleteQuery);

        if ($deleteResult) {
            // Decrement the 'scored' column in the Players table for the specified player
            $updateScoreQuery = "UPDATE Players SET appearances = appearances - 1 WHERE players_id = $players_id";
            if (!mysqli_query($con, $updateScoreQuery)) {
                echo "Error updating player score: " . mysqli_error($con);
            }

            header("Location: updateXi.php?updateid=$match_id");
        } else {
            echo mysqli_error($con);
        }
    } else {
        echo "Player not found or error retrieving player ID.";
    }
}
?>