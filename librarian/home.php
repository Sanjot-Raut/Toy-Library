<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/home_style.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        #allTheThings {
            max-width: 100%;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            width: calc(33.33% - 20px);
            /* Adjust width to fit 3 cards in a row */
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 10px;
        }

        /* Button Styles */
        .card a {
            text-decoration: none;
        }

        .card input[type="button"] {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            background-color: #ee5253;
            /* Red color for buttons */
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .card input[type="button"]:hover {
            background-color: #c0392b;
            /* Darker shade of red on hover */
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            left: 0;
        }
    </style>
</head>

<body>
    <div id="allTheThings">
        <div class="card">
            <a href="insert_toy.php"><input type="button" value="Insert New Toy Record"></a>
        </div>
        <div class="card">
            <a href="update_stock.php"><input type="button" value="Update Stock of a Toy"></a>
        </div>
        <div class="card">
            <a href="delete_toy.php"><input type="button" value="Delete Toys Records"></a>
        </div>
        <div class="card">
            <a href="display_toys.php"><input type="button" value="Display Available Toys"></a>
        </div>
        <div class="card">
            <a href="pending_requests.php"><input type="button" value="Manage Pending Toy Requests"></a>
        </div>
        <div class="card">
            <a href="pending_return_requests.php"><input type="button" value="Manage Pending Return Toy Requests"></a>
        </div>
        <div class="card">
            <a href="pending_registrations.php"><input type="button" value="Manage Pending Membership Registrations"></a>
        </div>
        <div class="card">
            <a href="update_membership.php"><input type="button" value="Update Membership of Members"></a>
        </div>
        <div class="card">
            <a href="view_feedbacks.php"><input type="button" value="View Feedbacks"></a>
        </div>
        <div class="card">
            <a href="generate_reports.php"><input type="button" value="Generate Reports"></a>
        </div>
        <div class="card">
            <a href="remove_member.php"><input type="button" value="Remove Member From Toy Library"></a>
        </div>
    </div>
    <footer>
        &copy; 2024 Toy Library. All rights reserved.
    </footer>
</body>

</html>