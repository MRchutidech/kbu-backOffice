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

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the new username already exists (excluding the current record)
    $checkQuery = "SELECT id FROM admin WHERE username = ? AND id != ?";
    $checkStmt = mysqli_prepare($con, $checkQuery);

    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "si", $username, $id);
        mysqli_stmt_execute($checkStmt);

        if (mysqli_stmt_fetch($checkStmt)) {
            // Username already exists (excluding the current record), show an error message
            echo "<script>
                    alert('This username is already in use by another account.');
                    window.location.href = '../index.php'; // Redirect back to the players page
                  </script>";
            exit();
        }

        mysqli_stmt_close($checkStmt);
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }

    // Prepare the statement for updating
    $updateQuery = "UPDATE admin SET username = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssi", $username, $password, $id);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Success
            header("Location: ../index.php");
            exit();
        } else {
            // Error
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
    }
}
?>
