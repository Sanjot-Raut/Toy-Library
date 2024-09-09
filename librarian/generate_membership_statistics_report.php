<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";

// Handle filtering for membership details
$filterType = isset($_GET['type']) ? $_GET['type'] : '';
$filterStartDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$filterEndDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$filterDuration = isset($_GET['duration']) ? $_GET['duration'] : '';

// Build SQL query for membership details with filters
$sqlMembershipDetails = "SELECT * FROM membership_details WHERE 1=1";
if (!empty($filterType)) {
    $sqlMembershipDetails .= " AND type = '$filterType'";
}
if (!empty($filterStartDate)) {
    $sqlMembershipDetails .= " AND start_date >= '$filterStartDate'";
}
if (!empty($filterEndDate)) {
    $sqlMembershipDetails .= " AND end_date <= '$filterEndDate'";
}
if (!empty($filterDuration)) {
    $sqlMembershipDetails .= " AND duration = $filterDuration";
}

// Execute the SQL query for membership details
$queryMembershipDetails = $con->query($sqlMembershipDetails);
$membershipDetails = $queryMembershipDetails->fetch_all(MYSQLI_ASSOC);

// Build SQL query for membership reports with filters
$sqlMembershipReports = "SELECT type,
                               COUNT(*) AS total_members,
                               SUM(amount_paid) AS total_revenue
                        FROM membership_details WHERE 1=1";
if (!empty($filterType)) {
    $sqlMembershipReports .= " AND type = '$filterType'";
}
if (!empty($filterStartDate)) {
    $sqlMembershipReports .= " AND start_date >= '$filterStartDate'";
}
if (!empty($filterEndDate)) {
    $sqlMembershipReports .= " AND end_date <= '$filterEndDate'";
}
if (!empty($filterDuration)) {
    $sqlMembershipReports .= " AND duration = $filterDuration";
}
$sqlMembershipReports .= " GROUP BY type";

// Execute the SQL query for membership reports
$queryMembershipReports = $con->query($sqlMembershipReports);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Statistics Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 20px;
}

.container {
    
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

thead {
    background-color: #ffcc00;
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
    background-color: #ffe066;
}

@media screen and (max-width: 768px) {
    .container {
        padding: 10px;
    }
    
    h2 {
        font-size: 24px;
    }
}

/* Style for membership status */
.status-active {
    color: #1abc9c;
    font-weight: bold;
}
.status-inactive {
    color: #e74c3c;
    font-weight: bold;
}

/* Style for type */
.type-basic {
    color: #3498db;
    font-weight: bold;
}
.type-premium {
    color: #f39c12;
    font-weight: bold;
}
.type-gold {
    color: #e67e22;
    font-weight: bold;
}
.type-platinum {
    color: #9b59b6;
    font-weight: bold;
}


    </style>
</head>
<body>
    <div class="container">
        <h2>Membership Details</h2>
        <!-- Filter options for membership details table -->
        <form method="GET">
            <label for="type">Filter by Type:</label>
            <select name="type" id="type">
                <option value="">All</option>
                <option value="Regular" <?php echo ($filterType == 'Regular') ? 'selected' : ''; ?>>Regular</option>
                <option value="Premium" <?php echo ($filterType == 'Premium') ? 'selected' : ''; ?>>Premium</option>
            </select>
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo $filterStartDate; ?>">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo $filterEndDate; ?>">
            <label for="duration">Filter by Duration:</label>
            <input type="number" name="duration" id="duration" placeholder="Duration (in months)" value="<?php echo $filterDuration; ?>">
            <button type="submit">Apply Filters</button>
        </form>

        <!-- Membership details table -->
        <table>
            <!-- Table header -->
            <thead>
                <tr>
                    <th>Membership ID</th>
                    <th>Member ID</th>
                    <th>Username</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Duration</th>
                    <th>Amount Paid</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody>
                <?php foreach ($membershipDetails as $membership): ?>
                <tr>
                    <!-- Display membership details -->
                    <?php
                        $query = $con->prepare("SELECT * FROM members WHERE id = ?");
                        $query->bind_param("i", $membership['member_id']);
                        $query->execute();
                        $member = $query->get_result()->fetch_assoc();
                    ?>
                    <td><?php echo $membership['id']; ?></td>
                    <td><?php echo $member['id']; ?></td>
                    <td><?php echo $member['username']; ?></td>
                    <td><?php echo $member['contact_number']; ?></td>
                    <td><?php echo $member['email']; ?></td>
                    <td><?php echo $membership['type']; ?></td>
                    <td><?php echo $membership['status']; ?></td>
                    <td><?php echo $membership['duration']; ?></td>
                    <td><?php echo $membership['amount_paid']; ?></td>
                    <td><?php echo $membership['start_date']; ?></td>
                    <td><?php echo $membership['end_date']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Membership Reports Section with Filters -->
     <div class="container">
        <h2>Membership Reports</h2>
        <!-- Filter options for membership reports table -->
        <!-- Your filter form for membership reports here -->

        <table>
    <thead>
        <tr>
            <th>Membership Type</th>
            <th>Total Members</th>
            <th>Total Revenue</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        $overallTotalRevenue = 0;
        $overallTotalMembers = 0;
        while ($row = $queryMembershipReports->fetch_assoc()):
            $overallTotalRevenue += $row['total_revenue'];
            $overallTotalMembers += $row['total_members'];
        ?>
        <tr>
            <td><?php echo $row['type']; ?></td>
            <td><?php echo $row['total_members']; ?></td>
            <td>Rs.<?php echo $row['total_revenue']; ?></td>
            
        </tr>
        <?php endwhile; ?>
        <!-- Overall Total Revenue row -->
        <tr>
            <td></td>
            <td><strong>Overall Total Members : <?php echo $overallTotalMembers; ?></strong>
            <td><strong>Overall Total Revenue : Rs.<?php echo $overallTotalRevenue; ?></strong>
        </tr>
    </tbody>
</table>

    </div>
</body>
</html>