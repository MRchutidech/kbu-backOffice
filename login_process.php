<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once "dbConfig.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data); 
        return $data;
    }

    $uname = validate($_POST['username']);
    $pass = validate($_POST['password']);
    if (empty($uname)) {
        header("Location: login.php?error=User Name is required");
        exit();
    } else if (empty($pass)) {
        header("Location: login.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM admin WHERE username='$uname' AND password='$pass'";

        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['username'] === $uname && $row['password'] === $pass) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['authenticated'] = true; // Set a session variable to mark authentication
    
                // Check if the logged-in admin is the main admin (based on role)
                if ($row['role'] === 'main_admin') {
                    $_SESSION['isAdminLoggedIn'] = true; // Set a session variable for main admin
                }
    
                header("Location: index.php");
                exit();
            } else {
                header("Location: login.php?error=Incorrect Username or Password");
                exit();
            }
        } else {
            header("Location: login.php?error=Incorrect Username or Password");
            exit();
        }
    }

} else {
    header("Location: login.php");
    exit();
}

?>