<?php
// process_payment.php

// Include database connection and any necessary functions
require_once "../db_connect.php";

// Check if membership ID and amount paid are provided
if (isset($_GET['membership_id']) && isset($_GET['amount_paid'])) {
    $membership_id = $_GET['membership_id'];
    $amount_paid = $_GET['amount_paid'];
    echo "Membership ID: $membership_id, Amount Paid: $amount_paid <br>";
    error_log("Membership ID: $membership_id, Amount Paid: $amount_paid");
    echo "<script>console.log('Membership ID: ". $membership_id ."')</script>";
    // Update the amount paid and status in the membership_details table
    $status = 'Active';
    $query = $con->prepare("UPDATE membership_details SET amount_paid = ?, status = ? WHERE id = ?");
    $query->bind_param("dsi", $amount_paid, $status, $membership_id);
    $query->execute();

    // Redirect back to the previous page with a success message
    header("Location: ".$_SERVER['HTTP_REFERER']."?success=1");
    exit();
} else {
    // Redirect back to the previous page with an error message if parameters are missing
    header("Location: ".$_SERVER['HTTP_REFERER']."?error=1");
    exit();
}
?>
