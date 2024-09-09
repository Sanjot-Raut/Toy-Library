<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";

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
<!DOCTYPE html>
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
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .toy-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .toy-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .toy-card h3 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }
        .toy-card:hover {
            transform: translateY(-5px);
        }
        .toy-details p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #666;
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
        }
    </style>
</head>
<body>
<form class="search-form" method="GET" id="filterForm">
    <input type="text" name="search" id="search" placeholder="Search by keyword" value="<?= htmlspecialchars($searchKeyword) ?>">
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

<div class="container" id="toyContainer">
    <?php if(empty($toys)): ?>
        <h2 align='center'>No toys available</h2>
    <?php else: ?>
        <?php foreach($toys as $toy): ?>
            <div class="toy-card">
                <?php if(!empty($toy['image_url'])): ?>
                    <img class="toy-image" src="../toys_images/<?= $toy['image_url'] ?>" alt="<?= $toy['toy_name'] ?>">
                <?php endif; ?>
                <h3><?= $toy['toy_name'] ?></h3>
                <div class="toy-details">
                    <p><strong>Category:</strong> <?= $toy['category'] ?></p>
                    <p><strong>Quantity:</strong> <?= $toy['quantity'] ?></p>
                    <p><strong>Available Quantity:</strong> <?= $toy['available_quantity'] ?></p>
                    <p><strong>Type:</strong> <?= $toy['type'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

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

</body>
</html>
