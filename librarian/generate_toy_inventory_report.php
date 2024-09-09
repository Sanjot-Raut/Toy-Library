<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";

// Function to calculate category and type counts
function calculateCounts($toys)
{
    $categoryCounts = array();
    $typeCounts = array();
    $totalCount = 0;
    $totalQuantity = 0;
    $totalAvailableQuantity = 0;

    // Iterate through toys to count each category, type, and total count
    foreach ($toys as $toy) {
        $category = $toy['category'];
        if (isset($categoryCounts[$category])) {
            $categoryCounts[$category]++;
        } else {
            $categoryCounts[$category] = 1;
        }

        $type = $toy['type'];
        if (isset($typeCounts[$type])) {
            $typeCounts[$type]++;
        } else {
            $typeCounts[$type] = 1;
        }

        $totalCount++;
        $totalQuantity += $toy['quantity'];
        $totalAvailableQuantity += $toy['available_quantity'];
    }

    return array(
        'categoryCounts' => $categoryCounts,
        'typeCounts' => $typeCounts,
        'totalCount' => $totalCount,
        'totalQuantity' => $totalQuantity,
        'totalAvailableQuantity' => $totalAvailableQuantity
    );
}

// Fetch toy data from the database
$toys = [];
$query = $con->query("SELECT * FROM toys");
$toys = $query->fetch_all(MYSQLI_ASSOC);

// Initialize category, type counts, total count, total quantity, and total available quantity
$counts = calculateCounts($toys);
$categoryCounts = $counts['categoryCounts'];
$typeCounts = $counts['typeCounts'];
$totalCount = $counts['totalCount'];
$totalQuantity = $counts['totalQuantity'];
$totalAvailableQuantity = $counts['totalAvailableQuantity'];
$totalBorrowedQuantiy = $totalQuantity - $totalAvailableQuantity;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Inventory Report</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            margin-top: 0;
            font-size: 32px;
            color:#ffcc00;
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

        th,
        td {
            padding: 12px;
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

        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }

        #search {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 60%;
            font-size: 16px;
        }


        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            h2 {
                font-size: 24px;
            }

            #search {
                width: 100%;
            }
        }

        .container-second {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .right {
            flex: 1;
            /* Take up remaining space */
            margin-right: 20px;
            /* Add some space between the two divs */
        }

        .left {
            flex: 1;
            /* Take up remaining space */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Toy Inventory Report</h2>
        <form class="search-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" placeholder="Enter keyword">
        </form>

        <table id="toy-table">
            <thead>
                <tr>
                    <th>Toy ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Quantity Available</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($toys as $toy) : ?>
                    <tr>
                        <td><?= $toy['id'] ?></td>
                        <td><?= $toy['toy_name'] ?></td>
                        <td><?= $toy['description'] ?></td>
                        <td><?= $toy['category'] ?></td>
                        <td><?= $toy['quantity'] ?></td>
                        <td><?= $toy['available_quantity'] ?></td>
                        <td><?= $toy['type'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="container-second">
            <div class="right">
                <h2>Toys Count</h2>
                <ul>
                    <li>
                        <h3 id="total-count">Total : <?= $totalCount ?></h3>
                    </li>
                    <li>
                        <h3>Category Wise :-</h3>
                        <ul id="category-counts">
                            
                            <?php foreach ($categoryCounts as $category => $count) : ?>
                                <li><?= $category ?>: <?= $count ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li>
                        <h3>Type Wise :-</h3>
                        <ul id="type-counts">
                            <?php foreach ($typeCounts as $type => $count) : ?>
                                <li><?= $type ?>: <?= $count ?></li>
                                <?php endforeach; ?>
                            </ul>
                    </li>
                </ul>
            </div>
            <div class="left">
                <h2>Toys Quantity</h2>
                <ul id="total-quantity">
                    <li>
                        <h3>Total :
                            <?= $totalQuantity ?></h3>
                    </li>
                </ul>
                <ul id="total-available-quantity">
                    <li>
                        <h3>Total Available :
                            <?= $totalAvailableQuantity ?></h3>
                    </li>
                </ul>
                <ul id="total-available-quantity">

                    <li>
                        <h3>Total Borrowed :<?= $totalBorrowedQuantiy ?></h3>
                    </li>
                </ul>
            </div>
        </div>


        <script>
            function updateCounts() {
                let keyword = document.getElementById('search').value.toLowerCase();
                let tableRows = document.querySelectorAll('#toy-table tbody tr');
                let totalCount = 0;
                let totalQuantity = 0;
                let totalAvailableQuantity = 0;
                let categoryCounts = {};
                let typeCounts = {};

                tableRows.forEach(row => {
                    let found = false;
                    row.querySelectorAll('td:not(:nth-child(5)):not(:nth-child(6))').forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(keyword)) {
                            found = true;
                        }
                    });
                    if (found) {
                        row.style.display = '';
                        totalCount++;
                        totalQuantity += parseInt(row.querySelector('td:nth-child(5)').textContent);
                        totalAvailableQuantity += parseInt(row.querySelector('td:nth-child(6)').textContent);
                        let category = row.querySelector('td:nth-child(4)').textContent;
                        let type = row.querySelector('td:nth-child(7)').textContent;
                        categoryCounts[category] = (categoryCounts[category] || 0) + 1;
                        typeCounts[type] = (typeCounts[type] || 0) + 1;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('total-count').textContent = `Total Count of Toys: ${totalCount}`;

                document.getElementById('total-quantity').innerHTML = `<li>Total: ${totalQuantity}</li>`;

                document.getElementById('total-available-quantity').innerHTML = `<li>Total: ${totalAvailableQuantity}</li>`;

                let categoryCountsList = document.getElementById('category-counts');
                categoryCountsList.innerHTML = '';
                Object.entries(categoryCounts).forEach(([category, count]) => {
                    let li = document.createElement('li');
                    li.textContent = `${category}: ${count}`;
                    categoryCountsList.appendChild(li);
                });

                let typeCountsList = document.getElementById('type-counts');
                typeCountsList.innerHTML = '';
                Object.entries(typeCounts).forEach(([type, count]) => {
                    let li = document.createElement('li');
                    li.textContent = `${type}: ${count}`;
                    typeCountsList.appendChild(li);
                });
            }

            document.getElementById('search').addEventListener('input', updateCounts);
        </script>
</body>

</html>