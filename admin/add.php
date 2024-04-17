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

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to insert data into the admin table
    $insertQuery = "INSERT INTO admin (username, password) VALUES (?, ?)";

    // Initialize a prepared statement
    $stmt = mysqli_stmt_init($con);

    if (mysqli_stmt_prepare($stmt, $insertQuery)) {
        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);

        // Check if the username already exists
        $checkQuery = "SELECT * FROM admin WHERE username = ?";
        $checkStmt = mysqli_stmt_init($con);

        if (mysqli_stmt_prepare($checkStmt, $checkQuery)) {
            mysqli_stmt_bind_param($checkStmt, "s", $username);
            mysqli_stmt_execute($checkStmt);

            if (mysqli_stmt_fetch($checkStmt)) {
                // Username already exists, show an error message
                echo "<script>
                        alert('This account already exists.');
                        window.location.href = '../index.php'; // Redirect back to the players page
                      </script>";
                exit();
            }

            mysqli_stmt_close($checkStmt);
        }

        if (mysqli_stmt_execute($stmt)) {
            // Success
            header("Location: ../index.php"); // Redirect to a success page
            exit();
        } else {
            // Error
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Error preparing the statement
        echo "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
}
?>
