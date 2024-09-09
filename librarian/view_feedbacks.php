<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";

    // Retrieve feedbacks from the database
    $query = $con->query("SELECT f.*, m.full_name, t.toy_name FROM feedbacks f
              JOIN members m ON f.member_id = m.id
              JOIN toys t ON f.toy_id = t.id");
    $feedbacks = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Member Feedbacks</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
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

        /* Styles for filtering options */
        .filter-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-container label {
            margin-right: 10px;
        }

        .filter-container input {
            margin-right: 20px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Member Feedbacks</h2>

        <!-- Filtering options -->
        <div class="filter-container">
            <label for="filter-member-id">Filter by Member ID:</label>
            <input type="text" id="filter-member-id" oninput="applyFilters()">
            <label for="filter-toy-id">Filter by Toy ID:</label>
            <input type="text" id="filter-toy-id" oninput="applyFilters()">
        </div>

        <!-- Feedbacks table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member ID</th>
                    <th>Member Name</th>
                    <th>Toy ID</th>
                    <th>Toy Name</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Feedback Date</th>
                </tr>
            </thead>
            <tbody id="feedbacks-table-body">
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?= $feedback['id'] ?></td>
                        <td><?= $feedback['member_id'] ?></td>
                        <td><?= $feedback['full_name'] ?></td>
                        <td><?= $feedback['toy_id'] ?></td>
                        <td><?= $feedback['toy_name'] ?></td>
                        <td><?= $feedback['rating'] ?></td>
                        <td><?= $feedback['comments'] ?></td>
                        <td><?= $feedback['feedback_date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function applyFilters() {
            var filterMemberId = document.getElementById('filter-member-id').value.toLowerCase();
            var filterToyId = document.getElementById('filter-toy-id').value.toLowerCase();
            var feedbacksRows = document.querySelectorAll('#feedbacks-table-body tr');

            feedbacksRows.forEach(row => {
                var memberId = row.getElementsByTagName('td')[1].innerText.toLowerCase();
                var toyId = row.getElementsByTagName('td')[2].innerText.toLowerCase();

                // Show or hide rows based on filters
                if (memberId.includes(filterMemberId) && toyId.includes(filterToyId)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
