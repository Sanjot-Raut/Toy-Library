<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";

// Check if the request to make a toy request is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    // Get the toy_id from the POST request
    $toyId = $_POST["id"];
    // Get the member_id from the session (assuming it's stored after member verification)
    $memberId = $_SESSION['id']; // Update this with the correct session variable name

    // Check if the member has already REQUESTED for the same toy
        $check_query = $con->prepare("SELECT COUNT(*) AS count FROM requests WHERE toy_id = ? AND member_id = ? AND status='Pending'");
        $check_query->bind_param("ii", $toyId, $memberId);
        $check_query->execute();
        $result = $check_query->get_result();
        $row = $result->fetch_assoc();
        $existing_requests = $row['count'];

        if ($existing_requests > 0) {
            // If the member has already requested the same toy, display a message
            echo "<script>alert('You have already requested this toy , Please wait for the Librarians response.');</script>";
        } else {




    // Prepare and execute the SQL query to insert the toy request into the toy_requests table
 $query = $con->prepare("SELECT COUNT(*) AS count FROM requests WHERE toy_id = ? AND member_id = ? AND status='Approved' AND id IN (SELECT request_id FROM toy_logs WHERE status='Borrowed');");
    $query->bind_param("ii", $toyId, $memberId);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $numBorrowed = $row['count'];

    if ($numBorrowed > 0) {
        // If the member has already borrowed the same toy, display a message
        echo "<script>alert('You have already borrowed this toy.');</script>";
    } else {











    $query = $con->prepare("SELECT num_toys_borrowed FROM membership_details WHERE member_id = ? AND id = (SELECT MAX(id) FROM membership_details WHERE member_id = ?)");
$query->bind_param("ii", $memberId, $memberId);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();
$num_toys_borrowed = $row['num_toys_borrowed'];

// Step 2: Check if num_toys_borrowed is greater than 0
if ($num_toys_borrowed > 0) {


    $query = $con->prepare("INSERT INTO requests (toy_id, member_id) VALUES (?, ?)");
    $query->bind_param("ii", $toyId, $memberId);

    if ($query->execute()) {
        // Toy request inserted successfully
        echo "<script>alert('Toy request successful!');</script>";
    } else {
        // Failed to insert toy request
        echo "<script>alert('Failed to request toy. Please try again later.');</script>";
    }
    } else {
        echo "<script>alert('You have reached the limit of borrowing toys simultaneously.');</script>";
    }
}
}
}



// Fetch distinct toy categories
$query = $con->query("SELECT DISTINCT category FROM toys");
$categories = $query->fetch_all(MYSQLI_ASSOC);

// Fetch distinct toy types
$query = $con->query("SELECT DISTINCT type FROM toys");
$types = $query->fetch_all(MYSQLI_ASSOC);

// Initialize filter variables
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Construct SQL query with filters and search keyword
$sql = "SELECT * FROM toys WHERE 1=1";
if (!empty($categoryFilter)) {
    $sql .= " AND category = '$categoryFilter'";
}
if (!empty($typeFilter)) {
    $sql .= " AND type = '$typeFilter'";
}
if (!empty($searchKeyword)) {
    $sql .= " AND (toy_name LIKE '%$searchKeyword%' OR category LIKE '%$searchKeyword%' OR type LIKE '%$searchKeyword%')";
}

$query = $con->query($sql);
$toys = $query->fetch_all(MYSQLI_ASSOC);


?>
<<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .toy-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .toy-card:hover {
            transform: translateY(-5px);
        }

        .toy-card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .toy-card h3 {
            margin: 0;
            font-size: 1.2em;
            color: #333;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 10px;
        }

        .toy-details p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #666;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .toy-details strong {
            color: #333;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"],
        .search-form select {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            display: none; /* Hide the button */
        }
        .request-button {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .request-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <form class="search-form" method="GET" id="filterForm">
    <input type="text" name="search" placeholder="Search by keyword" value="<?= htmlspecialchars($searchKeyword) ?>">
    <select name="category" id="category">
        <option value="">Filter by category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category['category']) ?>" <?= $category['category'] === $categoryFilter ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['category']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="type" id="type">
        <option value="">Filter by type</option>
        <?php foreach ($types as $type): ?>
            <option value="<?= htmlspecialchars($type['type']) ?>" <?= $type['type'] === $typeFilter ? 'selected' : '' ?>>
                <?= htmlspecialchars($type['type']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Apply Filters</button>
</form>

    <div class="container">
        <?php if (empty($toys)) : ?>
            <h2 align='center'>No toys available</h2>
        <?php else : ?>
            <?php foreach ($toys as $toy) : ?>
                <div class="toy-card">
                    <?php if (!empty($toy['image_url'])) : ?>
                        <img class="toy-image" src="../toys_images/<?= $toy['image_url'] ?>" alt="<?= $toy['toy_name'] ?>">
                    <?php endif; ?>
                    <h3><?= $toy['toy_name'] ?></h3>
                    <div class="toy-details">
                        <p><strong>Category:</strong> <?= $toy['category'] ?></p>
                        <p><strong>Quantity:</strong> <?= $toy['quantity'] ?></p>
                        <p><strong>Available Quantity:</strong> <?= $toy['available_quantity'] ?></p>
                        <p><strong>Type:</strong> <?= $toy['type'] ?></p>
                    </div>
                    <form method="post" action="#">
    <input type="hidden" name="id" value="<?= $toy['id'] ?>">
    <button type="submit" class="request-button">Request Toy</button>
</form>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
<script>
    // Function to submit the filter form when filter inputs change
    document.querySelectorAll('.search-form input, .search-form select').forEach(input => {
        input.addEventListener('input', () => {
            console.log('Form submitted');
            document.getElementById('filterForm').submit();
            var searchBox = document.getElementById('search');
            searchBox.focus(); // Keep focus on the search box
            searchBox.setSelectionRange(searchBox.value.length, searchBox.value.length); // Set cursor to end
        });
    });
</script>
</html>
