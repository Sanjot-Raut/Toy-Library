<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";

    // Fetch transaction data from the toy_logs table
    
    $query = $con->query("SELECT * FROM toy_logs");
    $logs = $query->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {

            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        .filter-container {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .filter-container input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
            margin-bottom: 10px;
            flex: 1 1 300px; /* Flexbox properties to allow input fields to grow */
        }

        /* Responsive table */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }

            h2 {
                font-size: 24px;
            }

            .filter-container input {
                flex: 1 1 100%; /* Adjust input fields to take full width on smaller screens */
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Transaction History Report</h2>
        <!-- Filter/Search Options -->
        <div class="filter-container">
            <input type="text" id="log-id-filter" oninput="filterTable('log-id-filter', 0)" placeholder="Filter by Log ID">
            <input type="text" id="member-id-filter" oninput="filterTable('member-id-filter', 1)" placeholder = "Filter by Member ID">
            
         
            <input type="text" id="member-name-filter" oninput="filterTable('member-name-filter', 2)" placeholder="Filter by Member Name">
            
            
            <input type="text" id="toy-name-filter" oninput="filterTable('toy-name-filter', 5)" placeholder="Filter by Toy Name">
           
            <input type="text" id="status-filter" oninput="filterTable('status-filter', 11)" placeholder="Filter by Status">
        </div>

        <!-- Transaction History Table -->
        <table id="transaction-table">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Member ID</th>
                    <th>Member Name</th>
                    <th>Member Contact</th>
                    <th>Toy ID</th>
                    <th>Toy Name</th>
                    <th>Toy Description</th>
                    <th>Toy Category</th>
                    <th>Confirmation Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log['log_id'] ?></td>
                        <?php
                            $query = $con->prepare("SELECT * FROM members WHERE id = (SELECT member_id FROM requests WHERE id=?);");
                            $query->bind_param("i", $log['request_id']);
                            $query->execute();
                            $member = $query->get_result()->fetch_assoc();
                        ?>
                        <td><?= $member['id'] ?></td>
                        <td><?= $member['full_name'] ?></td>
                        <td><?= $member['contact_number'] ?></td>
                        <?php
                            $query = $con->prepare("SELECT * FROM toys WHERE id = (SELECT toy_id FROM requests WHERE id=?);");
                            $query->bind_param("i", $log['request_id']);
                            $query->execute();
                            $toy = $query->get_result()->fetch_assoc();
                        ?>
                        <td><?= $toy['id'] ?></td>
                        <td><?= $toy['toy_name'] ?></td>
                        <td><?= $toy['description'] ?></td>
                        <td><?= $toy['category'] ?></td>
                        <td><?= $log['confirmation_date'] ?></td>
                        <td><?= $log['due_date'] ?></td>
                        <td><?= $log['return_date'] ?></td>
                        <td><?= $log['status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    function filterTable(inputId, columnIndex) {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById(inputId);
        filter = input.value.toUpperCase();
        table = document.getElementById("transaction-table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[columnIndex];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</body>
</html>