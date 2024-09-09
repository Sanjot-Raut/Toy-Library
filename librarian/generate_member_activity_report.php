<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";

    // Retrieve member activity data
    $query = $con->query("
        SELECT
            m.id AS member_id,
            m.username AS member_username,
            COUNT(DISTINCT r.id) AS num_borrowed_toys,
            COUNT(DISTINCT tl.return_date) AS num_returns,
            SUM(IF(tl.status = 'Returned' AND tl.return_date > tl.due_date, DATEDIFF(tl.return_date, tl.due_date), 0)) AS total_late_days,
            SUM(IF(tl.status = 'Returned' AND tl.return_date > tl.due_date, 1, 0)) AS num_late_returns,
            SUM(IF(tl.status = 'Returned' AND tl.return_date > tl.due_date, (DATEDIFF(tl.return_date, tl.due_date) * 0.5), 0)) AS total_fines
        FROM
            members m
        LEFT JOIN
            requests r ON m.id = r.member_id
        LEFT JOIN
            toy_logs tl ON r.id = tl.request_id
        GROUP BY
            m.id
    ");
    $memberActivity = $query->fetch_all(MYSQLI_ASSOC);

    // Data for the bar chart
    $memberUsernames = [];
    $numBorrowedToys = [];
    foreach ($memberActivity as $activity) {
        $memberUsernames[] = $activity['member_username'];
        $numBorrowedToys[] = $activity['num_borrowed_toys'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Activity Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <link rel="stylesheet" type="text/css" href="../css/table_styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
</head>
<style>
    /* Global Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-top: 0;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

.styled-table th,
.styled-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.styled-table th {
    background-color: #f2f2f2;
}

/* Canvas for Chart */
canvas {
    margin-top: 20px;
    display: block;
    width: 100%;
    max-width: 800px;
    height: auto;
}

</style>
<body>
    <div class="container">
        <h2>Member Activity Report</h2>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Member Username</th>
                    <th>Number of Toys Borrowed</th>
                    <th>Number of Returns</th>
                    <th>Total Late Days</th>
                    <th>Number of Late Returns</th>
                    <th>Total Fines</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($memberActivity as $activity): ?>
                    <tr>
                        <td><?= $activity['member_id'] ?></td>
                        <td><?= $activity['member_username'] ?></td>
                        <td><?= $activity['num_borrowed_toys'] ?></td>
                        <td><?= $activity['num_returns'] ?></td>
                        <td><?= $activity['total_late_days'] ?></td>
                        <td><?= $activity['num_late_returns'] ?></td>
                        <td><?= $activity['total_fines'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Bar chart to visualize number of toys borrowed by each member -->
        <canvas id="borrowedToysChart" width="800" height="400"></canvas>
    </div>

    <script>
        // Get the canvas element
        var ctx = document.getElementById('borrowedToysChart').getContext('2d');

        // Prepare data for the chart
        var usernames = <?= json_encode($memberUsernames) ?>;
        var borrowedToys = <?= json_encode($numBorrowedToys) ?>;

        // Create the bar chart
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: usernames,
                datasets: [{
                    label: 'Number of Toys Borrowed',
                    data: borrowedToys,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Number of Toys Borrowed by Each Member'
                }
            }
        });
    </script>
</body>
</html>
