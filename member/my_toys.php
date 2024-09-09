<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
    // Fetch Toys
    $member_id = $_SESSION['id']; // Replace 35 with the actual member ID

$query = $con->prepare("SELECT * FROM toys where id in (select toy_id from requests where id in (SELECT request_id FROM toy_logs WHERE status='Borrowed') AND member_id= ?);");
$query->bind_param("i", $member_id);
$query->execute();
$toys = $query->get_result()->fetch_all(MYSQLI_ASSOC);


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
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .toy-card h3 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }
        .toy-details p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #666;
            line-height: 1.4;
        }
        .toy-details strong {
            color: #333;
        }
    </style>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</head>
<body>

<div class="container">
    <?php if(empty($toys)): ?>
        <h2 align='center'>No borrowed toys</h2>
    <?php else: ?>
        <?php foreach($toys as $toy): ?>
    <div class="toy-card">
        <?php if(!empty($toy['image_url'])): ?>
            <img class="toy-image" src="../toys_images/<?= $toy['image_url'] ?>" alt="<?= $toy['toy_name'] ?>">
        <?php endif; ?>
        <h3><?= $toy['toy_name'] ?></h3>
        <div class="toy-details">
            <!-- Display toy details -->
            <p><strong>Toy ID :</strong> <?= $toy['id'] ?></p>
            <p><strong>Category:</strong> <?= $toy['category'] ?></p>
            <p><strong>Description:</strong> <?= $toy['description'] ?></p>
            <p><strong>Type:</strong> <?= $toy['type'] ?></p>
            
            <!-- Fetch due date and log ID for the current toy -->
            <?php 
                $toy_id = $toy["id"];
                $query = $con->prepare("SELECT * FROM toy_logs WHERE request_id IN (SELECT id FROM requests WHERE member_id = ? AND toy_id = ? AND status='Approved') AND status = 'Borrowed';");
                $query->bind_param("ii", $member_id, $toy_id);
                $query->execute();
                $toy_logs = $query->get_result()->fetch_assoc();
            ?>
            <!-- Display due date and log ID -->
            <p><strong>Due Date:</strong> <?= $toy_logs['due_date'] ?></p>
            <p><strong>Log ID:</strong> <?= $toy_logs['log_id'] ?></p>
            
            <!-- Return request form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="log_id" value="<?= $toy_logs['log_id'] ?>">
                        <input type="hidden" name="toy_id" value="<?= $toy_id ?>">
                        <button type="submit" name="return_toy">Return Toy</button>
                    </form>
        </div>
    </div>
<?php endforeach; ?>
    <?php endif; ?>

</div>
<?php
    // Handle return request
    if(isset($_POST['return_toy'])) {
        $log_id = $_POST['log_id'];
        $toy_id = $_POST['toy_id'];
        
        // Check if a return request already exists for the given log_id and toy_id
        $check_query = $con->prepare("SELECT COUNT(*) AS count FROM return_requests WHERE log_id = ? AND toy_id = ? AND status='Pending'");
        $check_query->bind_param("ii", $log_id, $toy_id);
        $check_query->execute();
        $result = $check_query->get_result();
        $row = $result->fetch_assoc();
        $existing_requests = $row['count'];

        if($existing_requests > 0) {
            // A return request already exists for the same toy and log
            echo "<script>alert('A return request for this toy has already been submitted, Please wait for the Librarians Response!!');</script>";
        } else {
            // Insert return request into return_requests table
            $return_query = $con->prepare("INSERT INTO return_requests (log_id, member_id, toy_id, status) VALUES (?, ?, ?, 'Pending')");
            $return_query->bind_param("iii", $log_id, $member_id, $toy_id);
            if($return_query->execute()) {
                echo "<script>alert('Return request submitted successfully.');</script>";
            } else {
                echo "<script>alert('Failed to submit return request.');</script>";
            }
        }
    }
?>

</body>
</html>
