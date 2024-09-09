<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";

     $query = $con->query("
        SELECT r.id AS request_id, t.toy_name, m.full_name AS member_name, tl.due_date, tl.return_date
        FROM requests r
        INNER JOIN toys t ON r.toy_id = t.id
        INNER JOIN members m ON r.member_id = m.id
        INNER JOIN toy_logs tl ON r.id = tl.request_id
        WHERE tl.status = 'Returned' AND tl.return_date > tl.due_date
    ");
    $lateReturns = $query->fetch_all(MYSQLI_ASSOC);
    // Retrieve data for late returns
    $query = $con->query("
        SELECT DATE(tl.return_date) AS return_date, COUNT(*) AS late_count
        FROM requests r
        INNER JOIN toy_logs tl ON r.id = tl.request_id
        WHERE tl.status = 'Returned' AND tl.return_date > tl.due_date
        GROUP BY DATE(tl.return_date)
        ORDER BY DATE(tl.return_date)
    ");
    $lateReturnsData = $query->fetch_all(MYSQLI_ASSOC);

    // Prepare data for the chart
    $labels = [];
    $data = [];
    foreach ($lateReturnsData as $record) {
        $labels[] = $record['return_date'];
        $data[] = $record['late_count'];
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Late Returns Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <link rel="stylesheet" type="text/css" href="../css/table_styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
</head>
<style>
    /* CSS for Late Returns Report */
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.styled-table th,
.styled-table td {
    border: 1px solid #dddddd;
    padding: 8px;
    text-align: left;
}

.styled-table th {
    background-color: #f2f2f2;
}

canvas {
    margin-top: 20px;
    display: block;
    margin-left: auto;
    margin-right: auto;
    max-width: 100%;
}

</style>
<body>
    <div class="container">
        <h2>Late Returns Report</h2>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Toy Name</th>
                    <th>Member Name</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Lateness Duration (Days)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lateReturns as $return): ?>
                    <tr>
                        <td><?= $return['request_id'] ?></td>
                        <td><?= $return['toy_name'] ?></td>
                        <td><?= $return['member_name'] ?></td>
                        <td><?= $return['due_date'] ?></td>
                        <td><?= $return['return_date'] ?></td>
                        <td><?= (strtotime($return['return_date']) - strtotime($return['due_date'])) / (60 * 60 * 24) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <canvas id="lateReturnsChart" width="800" height="400"></canvas> <!-- Canvas element for the chart -->
    </div>

    <script>
        // Get the canvas element
        var ctx = document.getElementById('lateReturnsChart').getContext('2d');

        // Create the line chart
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Late Returns',
                    data: <?= json_encode($data) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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
                    text: 'Late Returns Over Time'
                }
            }
        });
    </script>
</body>
</html>
