<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php"; // Verify librarian authentication
require "header_librarian.php"; // Header for librarian interface

// Fetch all members with their membership details
$query = $con->query("SELECT * FROM members");
$members = $query->fetch_all(MYSQLI_ASSOC);

// Handle form submission for updating membership details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_update"])) {
    // Process form data for updating membership
    $member_id = $_POST['member_id'];
    $membership_type = $_POST['membership_type'];
    $duration = $_POST['months'];
    $status = 'Inactive';
    $a = 0;
    $insertMembershipQuery = $con->prepare("INSERT INTO membership_details (member_id, type, duration, status, amount_paid) VALUES (?, ?, ?, ?, ?)");
    $insertMembershipQuery->bind_param("isisd", $member_id, $membership_type, $duration, $status, $a);
    $insertMembershipQuery->execute();
    $membership_id = $con->insert_id;

    $amount = calculateamountpaid($membership_type, $duration);
    echo "<script>
				
                        var amountPaid = $amount;
                        var confirmPayment = confirm('Confirm payment of ' + amountPaid + ' rupees?');
                        if (confirmPayment) {
                            window.location.href = 'process_payment.php?membership_id=$membership_id&amount_paid=$amount';
                        } else {
                            // Handle cancellation if needed
                        }
                      </script>";
}








function calculateAmountPaid($membership_type, $membership_duration)
{
    // Define the rates for different membership types in INR
    $regular_rate_per_month = 500; // INR 500 per month for regular membership
    $premium_rate_per_month = 1000; // INR 1000 per month for premium membership

    // Calculate the total amount based on membership type and duration
    if ($membership_type === 'Regular') {
        $total_amount = $regular_rate_per_month * $membership_duration;
    } elseif ($membership_type === 'Premium') {
        $total_amount = $premium_rate_per_month * $membership_duration;
    } else {
        // Handle invalid membership types
        $total_amount = 0;
    }

    return $total_amount;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <!-- Add additional stylesheets or inline styles here -->
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: lightgreen;
            font-weight: bold;
            color: #333;
        }

        tr:hover {
            background-color: lightgreen;
        }

        button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Ensure the popup is above other elements */
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
        }

        .popup-content form input,
        .popup-content form select {
            margin-bottom: 10px;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .popup-content form button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Media Query for Responsive Design */
        @media screen and (max-width: 600px) {
            .container {
                width: 100%;
                margin: 10px auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Membership Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Membership Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member) : ?>
                    <tr>
                        <td><?php echo $member['id']; ?></td>
                        <td><?php echo $member['username']; ?></td>
                        <td><?php echo $member['email']; ?></td>
                        <td><?php echo $member['contact_number']; ?></td>
                        <td><?php echo $member['address']; ?></td>

                        <?php
                        // Fetch the latest membership details for the member
                        $query = $con->prepare("SELECT * FROM membership_details WHERE member_id = ? ORDER BY id DESC LIMIT 1");
                        $query->bind_param("i", $member['id']);
                        $query->execute();
                        $membership_details = $query->get_result()->fetch_assoc();
                        ?>
                        <td><?php echo $membership_details['type']; ?></td>
                        <td><?php echo $membership_details['status']; ?></td>
                        <td>
                            <!-- Update membership form -->
                            <button type="button" onclick="showPopup(<?php echo $member['id']; ?>)">Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Popup overlay -->
    <div class="popup-overlay" id="popup">
        <!-- Popup content -->
        <div class="popup-content">
            <h3>Update Membership</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="member_id" id="member_id">
                <label for="months">Number of Months:</label>
                <input type="number" name="months" id="months" required>
                <label for="membership_type">Membership Type:</label>
                <select name="membership_type" id="membership_type" required>
                    <option value="Regular">Regular</option>
                    <option value="Premium">Premium</option>
                    <!-- Add more membership types if needed -->
                </select>
                <button type="submit" name="confirm_update">Confirm</button>
                <button type="button" onclick="hidePopup()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- JavaScript for popup functionality -->
    <script>
        function showPopup(memberId) {
            // Set the member ID in the hidden input field
            document.getElementById("member_id").value = memberId;
            // Display the popup
            document.getElementById("popup").style.display = "flex";
        }

        function hidePopup() {
            // Hide the popup
            document.getElementById("popup").style.display = "none";
        }
    </script>
</body>

</html>