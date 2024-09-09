<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";

    // Fetch feedback data from the database
    $feedbacks = [];
    $query = $con->query("SELECT * FROM feedbacks");
    $feedbacks = $query->fetch_all(MYSQLI_ASSOC);

    // Initialize variables for analysis
    $totalFeedbacks = count($feedbacks);
    $averageRating = calculateAverageRating($feedbacks);
    $satisfactionTrends = getSatisfactionTrends($feedbacks);

    // Function to calculate the average rating from feedback data
    function calculateAverageRating($feedbacks) {
        $totalRating = 0;
        foreach ($feedbacks as $feedback) {
            $totalRating += $feedback['rating'];
        }
        return ($totalRating > 0) ? round($totalRating / count($feedbacks), 2) : 0;
    }

    // Function to get member satisfaction trends
    function getSatisfactionTrends($feedbacks) {
        $trends = array();
        foreach ($feedbacks as $feedback) {
            $date = date('Y-m', strtotime($feedback['feedback_date']));
            if (isset($trends[$date])) {
                $trends[$date]['count']++;
                $trends[$date]['total_rating'] += $feedback['rating'];
            } else {
                $trends[$date] = array('count' => 1, 'total_rating' => $feedback['rating']);
            }
        }
        $averageTrends = array();
        foreach ($trends as $date => $data) {
            $averageTrends[$date] = round($data['total_rating'] / $data['count'], 2);
        }
        return $averageTrends;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Analysis Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 28px;
            color: #333;
            margin-top: 0;
        }

        h3 {
            font-size: 20px;
            color: #666;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 8px;
            font-size: 18px;
        }

        canvas {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Feedback Analysis Report</h2>
        
        <h3>Total Feedbacks: <?= $totalFeedbacks ?></h3>
        
        <h3>Average Rating: <?= $averageRating ?></h3>

        <h3>Member Satisfaction Trends</h3>
        <ul>
            <?php foreach ($satisfactionTrends as $date => $rating): ?>
                <li><?= $date ?>: <?= $rating ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- Canvas element for the chart -->
        <canvas id="feedbackChart"></canvas>
    </div>

    <script>
        // Get the canvas element
        var ctx = document.getElementById('feedbackChart').getContext('2d');

        // Prepare data for the chart
        var labels = <?= json_encode(array_keys($satisfactionTrends)) ?>;
        var data = <?= json_encode(array_values($satisfactionTrends)) ?>;

        // Create the chart
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Average Rating',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
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
                }
            }
        });
    </script>
</body>
</html>
