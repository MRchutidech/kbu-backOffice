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

if (isset($_GET['closeid'])) {
    $predict_id = $_GET['closeid'];

    $sql = "UPDATE predict 
            SET predict_status = 'close' WHERE predict_id = '$predict_id'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $completeMsg = "Add Success";

        $predictionCheckQuery = "SELECT * FROM prediction WHERE predict_id = '$predict_id'";
        $predictionResult = mysqli_query($con, $predictionCheckQuery);

        if (mysqli_num_rows($predictionResult) > 0) {
            $predictionRows = mysqli_fetch_all($predictionResult, MYSQLI_ASSOC);
            $matchQuery = "SELECT mf.homeScore, mf.awayScore 
            FROM match_fixture mf
            JOIN predict p ON mf.match_id = p.match_id
            WHERE p.predict_id = '$predict_id'";
        
            $matchQResult = mysqli_query($con, $matchQuery);
            $matchRow = mysqli_fetch_assoc($matchQResult);
            $homeScore = $matchRow['homeScore'];
            $awayScore = $matchRow['awayScore'];
        
            // Loop through all prediction rows
            foreach ($predictionRows as $predictionRow) {
                // Check if prediction scores match the match scores
                if ($predictionRow['homeScore'] == $homeScore && $predictionRow['awayScore'] == $awayScore) {
                    // Increment member.point by 1
                    $member_id = $predictionRow['member_id'];
                    $incrementPointQuery = "UPDATE member SET points = points + 1 WHERE member_id = '$member_id'";
                    $incrementPointResult = mysqli_query($con, $incrementPointQuery);
                    $record = "you won predict";
                    $updateRecord = "INSERT INTO record (member_id,recordDescription) VALUES ('$member_id','$record')";
                    $resultA = mysqli_query($con, $updateRecord); // Update the winning bid
        
                    if (!$incrementPointResult) {
                        echo "Error updating member points: " . mysqli_error($con);
                    } else {
                        // Update prediction status to 1 for the same member_id
                        $updatePredictionStatusQuery = "UPDATE prediction SET status = 1 WHERE member_id = '$member_id'";
                        $updatePredictionStatusResult = mysqli_query($con, $updatePredictionStatusQuery);

                        if (!$updatePredictionStatusResult) {
                            echo "Error updating prediction status: " . mysqli_error($con);
                        }
                    }
                }
            }
        }
        header("location:update.php?updateid=$predict_id");
    } else {
        echo "ERROR: " . mysqli_error($con);
    }
}
?>
