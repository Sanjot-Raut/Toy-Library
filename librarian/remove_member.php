<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";

// Function to return deposit amount and update member deposit to 0
function returnDepositAndUpdate($member_id, $con)
{
    // Fetch deposit amount of the member
    $query = $con->prepare("SELECT deposit FROM members WHERE id = ?");
    $query->bind_param("i", $member_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $deposit = $row['deposit'];

    // Update member deposit to 0
    $query = $con->prepare("UPDATE members SET deposit = 0 WHERE id = ?");
    $query->bind_param("i", $member_id);
    $query->execute();

    return $deposit;
}




function deductDepositAndUpdate($member_id, $con)
{
    // Fetch deposit amount of the member
    $query = $con->prepare("SELECT deposit FROM members WHERE id = ?");
    $query->bind_param("i", $member_id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $deposit = $row['deposit'];

    // Update member deposit to 0
    $query = $con->prepare("UPDATE members SET deposit = -1 WHERE id = ?");
    $query->bind_param("i", $member_id);
    $query->execute();

    return $deposit;
}

// Process removal of member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_member"])) {
    $member_id = $_POST["member_id"];
    $deposit_returned = returnDepositAndUpdate($member_id, $con);

    if ($deposit_returned !== false) {
        // Use session to store the alert message and redirect
        session_start();
        $_SESSION['alert_message'] = "Member removed. Deposit returned: $deposit_returned";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent form resubmission
        exit();
    } else {
        // Use session to store the alert message and redirect
        session_start();
        $_SESSION['alert_message'] = "Error removing member.";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent form resubmission
        exit();
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ban_member"])) {
    $member_id = $_POST["member_id"];
    $deposit_deducted = deductDepositAndUpdate($member_id, $con);

    if ($deposit_deducted !== false) {
        // Use session to store the alert message and redirect
        session_start();
        $_SESSION['alert_message'] = "Member Banned . Deposit Deducted : $deposit_deducted";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent form resubmission
        exit();
    } else {
        // Use session to store the alert message and redirect
        session_start();
        $_SESSION['alert_message'] = "Error removing member.";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent form resubmission
        exit();
    }
}

// Fetch list of members
$query = $con->query("SELECT * FROM members where deposit > 0;");
$members = $query->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Remove Member</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .content {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            
            font-size: 24px;
            margin-bottom: 20px;
            color: red;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: lightcoral;
            font-weight: bold;
            color: #333;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: lightgoldenrodyellow;
        }

        .action-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action-buttons button.remove {
            background-color: #ff6347;
            color: white;
        }

        .action-buttons button.remove:hover {
            background-color: #d63031;
        }

        .action-buttons button.view {
            background-color: #4caf50;
            color: white;
        }

        .action-buttons button.view:hover {
            background-color: #2ecc71;
        }

        /* Search Box Styles */
        #searchMember,
        #searchOverdue {
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }

        #searchMember:focus,
        #searchOverdue:focus {
            border-color: #6b94ff;
        }
    </style>
</head>

<body>
    <div class="content">
        <h2>Remove Member</h2>
        <!-- Display alert message if set -->
        <?php
        if (isset($_SESSION['alert_message'])) {
            echo "<script>alert('{$_SESSION['alert_message']}');</script>";
            unset($_SESSION['alert_message']); // Clear session variable
        }
        ?>
        <input type="text" id="searchMember" placeholder="Search by ID or Username">
        <table id="membersTable">
            <tr>
                <th>Member ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Date of Birth</th>
                <th>Deposit Amount</th>
                <th>Action</th>
            </tr>
            <?php foreach ($members as $member) : ?>
                <tr>
                    <td><?php echo $member['id']; ?></td>
                    <td><?php echo $member['username']; ?></td>
                    <td><?php echo $member['full_name']; ?></td>
                    <td><?php echo $member['email']; ?></td>
                    <td><?php echo $member['contact_number']; ?></td>
                    <td><?php echo $member['date_of_birth']; ?></td>
                    <td><?php echo 'Rs.' . number_format($member['deposit'], 2); ?></td>
                    <td class="action-buttons">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirm('Are you sure you want to remove this member?');">
                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                            <button type="submit" name="remove_member" class="remove">Remove Member</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <script>
            document.getElementById("searchMember").addEventListener("input", function() {
                var searchValue = this.value.toLowerCase();
                var rows = document.getElementById("membersTable").rows;

                for (var i = 1; i < rows.length; i++) {
                    var id = rows[i].cells[0].textContent.toLowerCase();
                    var username = rows[i].cells[1].textContent.toLowerCase();

                    if (id.indexOf(searchValue) > -1 || username.indexOf(searchValue) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            });
        </script>

        <h2>Members with Overdue Toys</h2>
        <input type="text" id="searchOverdue" onkeyup="searchTable('overdueTable', 'searchOverdue')" placeholder="Search by ID or Username">
        <table id="overdueTable">
            <tr>
                <th>Member ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Date of Birth</th>
                <th>Deposit Amount</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch members with overdue toys
            $query = $con->query("SELECT * FROM members WHERE id IN (SELECT member_id FROM requests WHERE id IN (SELECT request_id FROM toy_logs WHERE due_date < CURDATE() AND status = 'Borrowed')) AND deposit > 0;");

            // Check if there are any overdue members
            if ($query->num_rows > 0) {
                // Loop through each member and display their details
                while ($row = $query->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['full_name']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['contact_number']}</td>";
                    echo "<td>{$row['date_of_birth']}</td>";
                    echo "<td>Rs.{$row['deposit']}</td>";
                    echo "<td class='action-buttons'>
                <form action='' method='post'>
                    <input type='hidden' name='member_id' value='{$row['id']}'>
                    <button type='submit' name='ban_member' class='remove'>Remove Member</button>
                </form>
              </td>";
                    echo "</tr>";
                }
            } else {
                // If there are no overdue members, display a message
                echo "<tr><td colspan='8'>No members have overdue toys.</td></tr>";
            }
            ?>

        </table>
    </div>

    <script>
        function searchTable(tableId, inputId) {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById(inputId);
            filter = input.value.toUpperCase();
            table = document.getElementById(tableId);
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        }
    </script>

</body>

</html>