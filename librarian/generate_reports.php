<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "verify_librarian.php";
    require "header_librarian.php";

    // Check if report type is set and valid
    if(isset($_POST['report-type'])) {
        $reportType = $_POST['report-type'];
        switch ($reportType) {
            case 'toy-inventory':
                header("Location: generate_toy_inventory_report.php");
                exit();
            case 'membership-statistics':
                header("Location: generate_membership_statistics_report.php");
                exit();
            case 'transaction-history':
                header("Location: generate_transaction_history_report.php");
                exit();
            case 'feedback-analysis':
                header("Location: generate_feedback_analysis_report.php");
                exit();
          
            case 'popular-toys':
                header("Location: generate_popular_toys_report.php");
                exit();
            case 'late-returns':
                header("Location: generate_late_returns_report.php");
                exit();
            case 'member-activity':
                header("Location: generate_member_activity_report.php");
                exit();
            default:
                echo "Invalid report type.";
                exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <style>
          body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px; /* Increased max-width */
            margin: 0 auto;
            padding: 40px; /* Increased padding */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 32px; /* Increased font size */
            color: #333;
            text-align: center;
            margin-bottom: 40px;
        }

        label {
            display: block;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 18px; /* Increased font size */
        }

        select, input[type="text"] {
            width: calc(100% - 22px);
            padding: 15px; /* Increased padding */
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 18px; /* Increased font size */
        }

        button {
            width: 100%;
            padding: 15px; /* Increased padding */
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 18px; /* Increased font size */
        }

        button:hover {
            background-color: #45a049;
        }

        .additional-options {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }

        .additional-options label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 18px; /* Increased font size */
        }

        .additional-options input[type="text"] {
            width: calc(100% - 22px);
            font-size: 18px; /* Increased font size */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Generate Reports</h2>
        <form action="" method="post">
            <label for="report-type">Select Report Type:</label>
            <select name="report-type" id="report-type">
                <option value="toy-inventory">Toy Inventory Report</option>
                <option value="membership-statistics">Membership Statistics Report</option>
                <option value="transaction-history">Transaction History Report</option>
                <option value="feedback-analysis">Feedback Analysis Report</option>
                
                <option value="popular-toys">Popular Toys Report</option>
                <option value="late-returns">Late Returns Report</option>
                <option value="member-activity">Member Activity Report</option>
            </select>

            <div class="additional-options" id="additional-options">
                <!-- Additional options for specific report types will be added dynamically using JavaScript -->
            </div>

            <button type="submit">Generate Report</button>
        </form>
    </div>

    <script>
        document.getElementById('report-type').addEventListener('change', function() {
            var additionalOptionsContainer = document.getElementById('additional-options');
            var reportType = this.value;
            var additionalOptionsHTML = '';

            // Clear previous additional options
            additionalOptionsContainer.innerHTML = '';

            // Add additional options based on selected report type
            switch (reportType) {
                case 'toy-inventory':
                    // No additional options needed for Toy Inventory Report
                    break;
                case 'membership-statistics':
                    // No additional options needed for Membership Statistics Report
                    break;
                case 'transaction-history':
                    // No additional options needed for Transaction History Report
                    break;
                case 'feedback-analysis':
                    // No additional options needed for Feedback Analysis Report
                    break;
               
                case 'popular-toys':
                    // No additional options needed for Popular Toys Report
                    break;
                case 'late-returns':
                    // No additional options needed for Late Returns Report
                    break;
                case 'member-activity':
                    // No additional options needed for Member Activity Report
                    break;
                default:
                    break;
            }

            // Append additional options HTML to the container
            additionalOptionsContainer.innerHTML = additionalOptionsHTML;
        });
    </script>
</body>
</html>
