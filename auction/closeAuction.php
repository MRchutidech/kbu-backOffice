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
require_once("../../kbufc-api/PromptPay-QR-generator/lib/PromptPayQR.php");
if (!$con) {
    die(mysqli_error($con));
}

if (isset($_GET['closeid'])) {
    $auction_id = $_GET['closeid'];

    // Use a prepared statement
    $sql = "UPDATE auction 
    SET status = 'close' WHERE auction_id = '$auction_id'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $completeMsg = "Add Success";
        $statusQuery = "SELECT status,endPrice FROM auction WHERE auction_id = '$auction_id'";
        $statusResult = mysqli_query($con, $statusQuery);
        $statusRow = mysqli_fetch_assoc($statusResult);
        $status = $statusRow['status'];
        $endP = $statusRow['endPrice'];


        if ($status == 'close') {
            if ($endP == null) {
                header("location:update.php?updateid=$auction_id");
            } else
                // Select the bid with the highest amount for this auction
                $bidSelectSql = "SELECT b.bid_id, b.member_id, b.amount, a.itemsName 
                 FROM bid AS b
                 INNER JOIN auction AS a ON b.auction_id = a.auction_id
                 WHERE b.auction_id = '$auction_id'
                 ORDER BY b.amount DESC LIMIT 1";
            $bidResult = mysqli_query($con, $bidSelectSql);
            $bidRow = mysqli_fetch_assoc($bidResult);
            $bid_id = $bidRow['bid_id'];
            $member_id = $bidRow['member_id'];
            $amount = $bidRow['amount'];
            $description = 'you won the auction item ' . $bidRow['itemsName'];

            $updateRecord = "INSERT INTO record (member_id,recordDescription) VALUES ('$member_id','$description')";
            $resultA = mysqli_query($con, $updateRecord); // Update the winning bid
            $updateBidSql = "UPDATE bid SET winningBid = 1 WHERE bid_id = '$bid_id'";
            mysqli_query($con, $updateBidSql);

            // Update the auction's end price
            $updateAuctionSql = "UPDATE auction SET endPrice = '$amount' WHERE auction_id = '$auction_id'";
            mysqli_query($con, $updateAuctionSql);

            // Insert an order into the order_member table
            $orderInsertSql = "INSERT INTO order_member (member_id, orderStatus, shippingCompany, paymentTotalprice, orderType) VALUES ('$member_id', 'waiting_payment', 'kerry', '$amount' + 19, 'auction')";
            $results = mysqli_query($con, $orderInsertSql);

            if ($results) {
                // Retrieve the last inserted order_id
                $order_id = mysqli_insert_id($con);

                // Insert a record into the order_items table
                $orderItemsInsertSql = "INSERT INTO order_items (order_id, auction_id, quantity) VALUES ('$order_id', '$auction_id', '1')";
                mysqli_query($con, $orderItemsInsertSql);

                $postData = array('orderId' => $order_id, 'paymentTotalprice' => $amount + 19);
                $postUrl = 'https://kbufc.kbu.cloud/kbufc-api/PromptPay-QR-generator/test.php'; // Replace with the actual URL of your QR code generator API
                $ch = curl_init($postUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($ch);
                curl_close($ch);
                // Handle the response from the QR code generator API (e.g., check for success)
                $responseData = json_decode($response, true);

                if ($responseData && isset($responseData['res']) && $responseData['res'] === 'success') {
                    echo "SUCCESS: ";
                } else {
                    echo "ERROR: ";
                }

            }

        }

        header("location:update.php?updateid=$auction_id");
    } else {
        echo "ERROR: " . mysqli_error($con);
    }
}
?>