<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php"; // Assuming you have a verification mechanism for librarians
require "header_librarian.php";

// Fetch pending toy requests
$query = $con->query("SELECT * FROM requests WHERE status = 'Pending'");
$requests = $query->fetch_all(MYSQLI_ASSOC);

// Handle form submissions for request approval or rejection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["approve"])) {
        $requestId = $_POST["request_id"];
        approveRequest($requestId);
        // Redirect to prevent form resubmission
        header("Location: pending_requests.php");
        exit();
    } elseif (isset($_POST["reject"])) {
        $requestId = $_POST["request_id"];
        rejectRequest($requestId);
        // Redirect to prevent form resubmission
        header("Location: pending_requests.php");
        exit();
    }
}
// Helper functions to approve or reject toy requests
function approveRequest($requestId)
{
    global $con;
    $query = $con->prepare("UPDATE requests SET status = 'Approved' WHERE id = ?");
    $query->bind_param("i", $requestId);
    if ($query->execute()) {
        echo "<script>alert('Request approved successfully!');</script>";
	} else {
        echo "<script>alert('Failed to approve request. Please try again later.');</script>";
    }
	



$query = $con->prepare("SELECT num_toys_borrowed FROM membership_details WHERE member_id = (SELECT member_id FROM requests WHERE id = ?) AND id = (SELECT MAX(id) FROM membership_details WHERE member_id = (SELECT member_id FROM requests WHERE id = ?))");
$query->bind_param("ii", $requestId, $requestId);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();
$num_toys_borrowed = $row['num_toys_borrowed'];

// Step 2: Check if num_toys_borrowed is greater than 0
if ($num_toys_borrowed > 0) {
    // Step 3: Insert record into toy_logs table
    $librarian_id = $_SESSION['id']; // Get librarian ID from session or wherever applicable
    $request_id = $requestId; // Get request ID from somewhere
    $insert_query = $con->prepare("INSERT INTO toy_logs (request_id, librarian_id) VALUES (?, ?)");
    $insert_query->bind_param("ii", $request_id, $librarian_id);
    $insert_query->execute();
    $insert_query->close();

    // Step 4: Decrease available_quantity of the toy by 1
    $update_query = $con->prepare("UPDATE toys SET available_quantity = available_quantity - 1 WHERE id = (SELECT toy_id FROM requests WHERE id = ?)");
    $update_query->bind_param("i",$requestId);
    $update_query->execute();
    $update_query->close();


	$updateQuery = $con->prepare("UPDATE membership_details AS md
JOIN (
    SELECT member_id
    FROM requests
    WHERE id = ?
) AS r ON md.member_id = r.member_id
SET md.num_toys_borrowed = md.num_toys_borrowed - 1
WHERE md.id = (
    SELECT MAX(id)
    FROM membership_details
    WHERE member_id = r.member_id
)
");
	$updateQuery->bind_param("i", $requestId);
	$updateQuery->execute();

}
else{

	echo "<script>alert('Member have Reached the Limit of Borrowing the Toy!');</script>";
}
}

function rejectRequest($requestId)
{
    global $con;
    $query = $con->prepare("UPDATE requests SET status = 'Rejected' WHERE id = ?");
    $query->bind_param("i", $requestId);
    if ($query->execute()) {
        echo "<script>alert('Request rejected successfully!');</script>";
    } else {
        echo "<script>alert('Failed to reject request. Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Toy Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
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

        th, td {
            padding: 12px;
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
            background-color: #ffe066;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[name="approve"] {
            background-color: #4caf50;
            color: #fff;
        }

        button[name="reject"] {
            background-color: #f44336;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Toy Requests</h2>
    <?php if (empty($requests)): ?>
        <p>No pending toy requests.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Toy ID</th>
                <th>Toy Name</th>
                <th>Member ID</th>
                <th>Member Name</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= $request['id'] ?></td>
                    <td><?= $request['toy_id'] ?></td>
                    <td><?= getToyName($request['toy_id']) ?></td>
                    <td><?= $request['member_id'] ?></td>
                    <td><?= getMemberName($request['member_id']) ?></td>
                    <td><?= $request['request_date'] ?></td>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                            <button type="submit" name="approve">Approve</button>
                            <button type="submit" name="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>

<?php

function getToyName($toyId)
{
    global $con;
    $query = $con->prepare("SELECT toy_name FROM toys WHERE id = ?");
    $query->bind_param("i", $toyId);
    $query->execute();
    $result = $query->get_result();
    $toy = $result->fetch_assoc();
    return $toy ? $toy['toy_name'] : "Unknown";
}

function getMemberName($memberId)
{
    global $con;
    $query = $con->prepare("SELECT username FROM members WHERE id = ?");
    $query->bind_param("i", $memberId);
    $query->execute();
    $result = $query->get_result();
    $member = $result->fetch_assoc();
    return $member ? $member['username'] : "Unknown";
}

?>
