<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";

    // Number of top toys to display
    $numTopToys = 5;

    // Retrieve data for popular toys based on borrowing frequency
    $query = $con->query("
        SELECT t.id, t.toy_name, t.description, t.category, COUNT(tl.log_id) AS borrowing_count
        FROM toys t
        LEFT JOIN requests r ON t.id = r.toy_id
        LEFT JOIN toy_logs tl ON r.id = tl.request_id
        WHERE tl.status = 'Borrowed'
        GROUP BY t.id
        ORDER BY borrowing_count DESC
        LIMIT $numTopToys
    ");
    $popularToysFrequency = $query->fetch_all(MYSQLI_ASSOC);

    // Retrieve data for popular toys based on average ratings
    $queryAvgRating = $con->query("
        SELECT t.id, t.toy_name, AVG(f.rating) AS avg_rating
        FROM toys t
        LEFT JOIN feedbacks f ON t.id = f.toy_id
        GROUP BY t.id
        ORDER BY avg_rating DESC
        LIMIT $numTopToys
    ");
    $popularToysRating = $queryAvgRating->fetch_all(MYSQLI_ASSOC);

    // Data for the bar chart
    $topToysNames = [];
    $topToysCounts = [];
    foreach ($popularToysFrequency as $toy) {
        $topToysNames[] = $toy['toy_name'];
        $topToysCounts[] = $toy['borrowing_count'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Toys Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <link rel="stylesheet" type="text/css" href="../css/table_styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
</head>
<style>
    /* Global styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

h3 {
    color: #666;
}

/* Table styles */
.styled-table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

.styled-table th,
.styled-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.styled-table th {
    background-color: #f2f2f2;
}

.styled-table tbody tr:hover {
    background-color: #f5f5f5;
}

/* Chart container styles */
#borrowingFrequencyChart {
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

</style>
<body>
    <div class="container">
        <h2>Popular Toys Report</h2>

        <h3>Based on Borrowing Frequency</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Toy ID</th>
                    <th>Toy Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Borrowing Frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($popularToysFrequency as $toy): ?>
                    <tr>
                        <td><?= $toy['id'] ?></td>
                        <td><?= $toy['toy_name'] ?></td>
                        <td><?= $toy['description'] ?></td>
                        <td><?= $toy['category'] ?></td>
                        <td><?= $toy['borrowing_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <canvas id="borrowingFrequencyChart" width="800" height="400"></canvas> <!-- Canvas element for the chart -->
    </div>

    <script>
        // Get the canvas element
        var ctx = document.getElementById('borrowingFrequencyChart').getContext('2d');

        // Prepare data for the chart
        var toyNames = <?= json_encode(array_column($popularToysFrequency, 'toy_name')) ?>;
        var borrowingFrequency = <?= json_encode(array_column($popularToysFrequency, 'borrowing_count')) ?>;

        // Create the bar chart
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: toyNames,
                datasets: [{
                    label: 'Borrowing Frequency',
                    data: borrowingFrequency,
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
                    text: 'Top Toys Based on Borrowing Frequency'
                }
            }
        });
    </script>
</body>
</html>
