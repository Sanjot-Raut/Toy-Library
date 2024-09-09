<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";

// Update Toy quantity if ID and quantity are provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toy_id']) && isset($_POST['quantity'])) {
    $toy_id = $_POST['toy_id'];
    $quantity = $_POST['quantity'];
    
    $query = $con->prepare("UPDATE toys SET quantity = ? WHERE id = ?");
    $query->bind_param("ii", $quantity, $toy_id);
    
    if ($query->execute()) {
        $_SESSION['update_success'] = "Toy quantity updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['update_error'] = "Failed to update toy quantity";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch Toys
$query = $con->query("SELECT * FROM toys");
$toys = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Toy Stock</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <link rel="stylesheet" type="text/css" href="../css/home_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        td {
            background-color: #fff;
            color: #666;
        }

        td a {
            color: #F66;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .error-message {
            margin-top: 20px;
            text-align: center;
            color: #f00;
        }

        .search-box {
            margin-bottom: 20px;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<h2>Update Toy Stock</h2>

<?php
if(isset($_SESSION['update_success'])) {
    echo success($_SESSION['update_success']);
    unset($_SESSION['update_success']);
}
if(isset($_SESSION['update_error'])) {
    echo error_without_field($_SESSION['update_error']);
    unset($_SESSION['update_error']);
}
?>

<input type="text" id="searchInput" class="search-box" placeholder="Search..." onkeyup="searchToy()">

<table id="toyTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Toy Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Available Quantity</th>
            <th>Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($toys as $toy): ?>
            <tr>
                <td><?= $toy['id'] ?></td>
                <td><?= $toy['toy_name'] ?></td>
                <td><?= $toy['category'] ?></td>
                <td><?= $toy['quantity'] ?></td>
                <td><?= $toy['available_quantity'] ?></td>
                <td><?= $toy['type'] ?></td>
                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="toy_id" value="<?= $toy['id'] ?>">
                        <input type="number" name="quantity" value="<?= $toy['quantity'] ?>" min="0" required>
                        <button type="submit">Update Quantity</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function searchToy() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("toyTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    }
</script>

</body>
</html>
