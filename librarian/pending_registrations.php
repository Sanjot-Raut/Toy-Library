<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
<head>
	<title>LMS</title>
	<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
	<link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
	<link rel="stylesheet" type="text/css" href="css/pending_registrations_style.css">
	<style>
		body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0px;
}

.container {
    max-width: 1500px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
	overflow: auto;
}

h2 {
    margin-top: 0;
    font-size: 32px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

thead {
    background-color: #007bff;
    color: #fff;
	
}

th, td {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
	
    font-weight: bold;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #cce5ff;
}

/* Error message styles */
.error-message {
	text-align: center;
    background-color: #f44336; /* Red background color */
    color: white; /* White text color */
    padding: 10px; /* Padding around the content */
    border-radius: 5px; /* Rounded corners */
    margin-bottom: 20px; /* Bottom margin for spacing */
	display: none; /* Hide the error message div by default */
}

.error-message p:not(:empty) {
    display: block; /* Display the error message div only when the p tag inside it contains text */
}

#error-message p {
    margin: 0; /* Remove default paragraph margin */
}
/* Button styles */
.approve-btn, .reject-btn {
    padding: 15px 30px;
    font-size: 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    text-transform: uppercase;
}

.approve-btn {
    background-color: #28a745; /* Green */
    color: #fff;
}

.reject-btn {
    background-color: #dc3545; /* Red */
    color: #fff;
}

.approve-btn:hover, .reject-btn:hover {
    filter: brightness(90%);
}

.approve-btn:active, .reject-btn:active {
    transform: translateY(1px);
    filter: brightness(80%);
}


/* Footer styles */
footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px 0;
    position: fixed;
    bottom: 0;
    width: 100%;
}

		</style>
</head>
<body>
	<?php
		$query = $con->prepare("SELECT username,full_name,email,membership_type,membership_duration,registration_date FROM pending_registrations;");
		$query->execute();
		$result = $query->get_result();
		$rows = mysqli_num_rows($result);
		if($rows == 0)
			echo "<h2 align='center'>No pending registrations at the moment!</h2>";
		else
		{
			echo "<form class='container' method='POST' action='#'>";
			echo "<center><h2>Pending Membership Registrations</h2></center>";
			echo "<div class='error-message' id='error-message'>
					<p id='error'></p>
				</div>";
			echo "<table>
					
            <thead>
					<tr>
						<th></th>
						<th>Username<hr></th>
						<th>Name<hr></th>
						<th>Email<hr></th>
						<th>Membership Type<hr></th>
						<th>Membership Duration (Months) <hr></th>
						<th>Registration Date<hr></th>
						<th>Deposit amount to be paid<hr></th>
						<th>Membership amount to be paid<hr></th>
						<th>Total amount to be paid<hr></th>
					</tr></thead>
                <tbody id='feedbacks-table-body'>";
			for($i=0; $i<$rows; $i++)
			{
				$row = mysqli_fetch_array($result);
				echo "<tr>";
				echo "<td>
						<label class='control control--radio'>
							<input type='radio' name='selected_user' value='".$row[0]."' />
							<div class='control__indicator'></div>
						</label>
					</td>";
				for($j=0; $j<6; $j++)
					echo "<td>".$row[$j]."</td>";

			$deposit = calculatedeposit($row[3]);
			echo "<td>Rs.".$deposit."</td>";
			$membershipamount = calculateAmountPaid($row[3], $row[4]);
			echo "<td>Rs.".$membershipamount."</td>";
			echo "<td>Rs.".$deposit+$membershipamount."</td>";
				echo "</tr>";

			}
			echo "</tbody></table><br /><br />";
			echo "<div style='float: right;'>";
			echo "<input class='approve-btn' type='submit' value='Approve' name='l_approve' />&nbsp;&nbsp;&nbsp;";
			echo "<input class='reject-btn' type='submit' value='Reject' name='l_reject' />";
			echo "</div>";
			echo "</form>";
		}
		
		
if(isset($_POST['l_approve'])) {
    $username = $_POST['selected_user'];
    if(empty($username)) {
        echo error_without_field("No user selected. Please select a user to approve.");
    } else {
        $query = $con->prepare("SELECT * FROM pending_registrations WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        if($row) {
            $query->close(); // Close previous query
            
            // Prepare and execute insert query for members table
            $insertMemberQuery = $con->prepare("INSERT INTO members(username, password, full_name, email, date_of_birth, address, contact_number, deposit, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $deposit = calculatedeposit($row['membership_type']);
            $insertMemberQuery->bind_param("ssssssids", $row['username'], $row['password'], $row['full_name'], $row['email'], $row['date_of_birth'], $row['address'], $row['contact_number'], $deposit, $row['profile_picture']);
            if($insertMemberQuery->execute()) {
                $member_id = $con->insert_id; // Get the inserted member_id
                
                // Prepare and execute insert query for membership_details table
                if($row['membership_type'] == 'Regular')
					$num_toys_borrowed=2;
				else
					$num_toys_borrowed=3;
                $status = 'Inactive';
				$a=0;
    $insertMembershipQuery = $con->prepare("INSERT INTO membership_details (member_id, type, duration, status, amount_paid,num_toys_borrowed) VALUES (?, ?, ?, ?,?, ?)");
    $insertMembershipQuery->bind_param("isisdi", $member_id, $row['membership_type'], $row['membership_duration'], $status, $a,$num_toys_borrowed);
    $insertMembershipQuery->execute();
                $membership_id = $con->insert_id;
                // Prompt confirmation dialog for payment
                $amount = calculateamountpaid($row['membership_type'], $row['membership_duration']);
                echo "<script>
				
                        var amountPaid = $amount;
                        var confirmPayment = confirm('Confirm payment of ' + amountPaid + ' rupees?');
                        if (confirmPayment) {
                            window.location.href = 'process_payment.php?membership_id=$membership_id&amount_paid=$amount';
                        } else {
                            // Handle cancellation if needed
                        }
                      </script>";
                
                // Delete from pending_registrations table
                $deleteQuery = $con->prepare("DELETE FROM pending_registrations WHERE username = ?");
                $deleteQuery->bind_param("s", $username);
                $deleteQuery->execute();
                
                // Send email notification
                $to = $row['email'];
                $subject = "Library Membership Approved";
                $message = "Your membership has been approved by the library. You can now enjoy the benefits of our library services.";
                mail($to, $subject, $message);
                
                echo success("Membership approved successfully!");
            } else {
                echo error_without_field("ERROR: Couldn't approve membership");
            }
        } else {
            echo error_without_field("No data found for the selected user.");
        }
    }
}

		if(isset($_POST['l_reject']))
		{
			$username = $_POST['selected_user'];
			if(empty($username))
				echo error_without_field("No user selected. Please select a user to reject.");
			else {
				$query = $con->prepare("SELECT email FROM pending_registrations WHERE username = ?;");
				$query->bind_param("s", $username);
				$query->execute();
				$email = mysqli_fetch_array($query->get_result())[0];
				
				$query = $con->prepare("DELETE FROM pending_registrations WHERE username = ?;");
				$query->bind_param("s", $username);
				if($query->execute()) {
					$to = $email;
					$subject = "Library Membership Rejected";
					$message = "Your membership registration has been rejected by the library. Please contact us for further information.";
					mail($to, $subject, $message);
					echo success("Membership rejected successfully!");
				} else {
					echo error_without_field("ERROR: Couldn't reject membership");
				}
			}
		}
		function calculatedeposit($membershipType){
			if($membershipType  == 'Regular') return 1500;
			if($membershipType  == 'Premium') return 2000;
		}
		function calculateAmountPaid($membership_type, $membership_duration) {
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
</body>
<footer>
    &copy; 2024 Toy Library. All rights reserved.
</footer>
</html>
