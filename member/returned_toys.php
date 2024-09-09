<?php
ob_start(); 
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
    // Fetch Toys
    $member_id = $_SESSION['id']; // Replace 35 with the actual member ID

$query = $con->prepare("SELECT * FROM toys where id in (select toy_id from requests where id in (SELECT request_id FROM toy_logs WHERE status='Returned') AND member_id= ?);");
$query->bind_param("i", $member_id);
$query->execute();
$toys = $query->get_result()->fetch_all(MYSQLI_ASSOC);



function hasProvidedFeedback($member_id, $toy_id, $con) {
        $query = $con->prepare("SELECT * FROM feedbacks WHERE member_id = ? AND toy_id = ?");
        $query->bind_param("ii", $member_id, $toy_id);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();
        return !empty($result);
    }
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
         /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border-radius: 5px;
        width: 80%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    /* Form styles */
    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    input[type="number"],
    textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }
    </style>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

     // Function to open the modal
        function openModal(toyId) {
            document.getElementById('feedbackModal').style.display = 'block';
            document.getElementById('toyIdInput').value = toyId;
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('feedbackModal').style.display = 'none';
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('feedbackModal');
            if (event.target == modal) {
                closeModal();
            }
        }
</script>

</head>
<body>


<div class="container">
    <?php if(empty($toys)): ?>
        <h2 align='center'>No Returned toys</h2>
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
                        $query = $con->prepare("SELECT * FROM toy_logs WHERE request_id IN (SELECT id FROM requests WHERE member_id = ? AND toy_id = ? AND status='Approved') AND status = 'Returned';");
                        $query->bind_param("ii", $member_id, $toy_id);
                        $query->execute();
                        $toy_logs = $query->get_result()->fetch_assoc();
                    ?>
                    <!-- Display due date and log ID -->
                    <p><strong>Due Date:</strong> <?= $toy_logs['due_date'] ?></p>
                    <p><strong>Log ID:</strong> <?= $toy_logs['log_id'] ?></p>
                    
                    <?php if (!hasProvidedFeedback($member_id, $toy['id'], $con)): ?>
                        <!-- Display feedback form if feedback has not been provided -->
                        <button onclick="openModal(<?= $toy['id'] ?>)">Provide Feedback</button>
                    <?php else: ?>
                        <p>You have already provided feedback for this toy.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal HTML -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <!-- Feedback form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="member_id" value="<?= $member_id ?>">
            <input type="hidden" id="toyIdInput" name="toy_id" value="">
            <label for="rating">Rating (out of 5 stars):</label>
            <input type="number" name="rating" id="rating" min="1" max="5" required>
            <label for="ease_of_use_rating">Ease of Use Rating (out of 5 stars):</label>
            <input type="number" name="ease_of_use_rating" id="ease_of_use_rating" min="1" max="5" required>
            <label for="toy_condition_rating">Toy Condition Rating (out of 5 stars):</label>
            <input type="number" name="toy_condition_rating" id="toy_condition_rating" min="1" max="5" required>
            <label for="overall_satisfaction_rating">Overall Satisfaction Rating (out of 5 stars):</label>
            <input type="number" name="overall_satisfaction_rating" id="overall_satisfaction_rating" min="1" max="5" required>
            <label for="comments">Comments:</label>
            <textarea name="comments" id="comments"></textarea>
            <label for="improvement_suggestions">Improvement Suggestions:</label>
            <textarea name="improvement_suggestions" id="improvement_suggestions"></textarea>
            <button type="submit" name="submit_feedback">Submit Feedback</button>
        </form>
    </div>
</div>
<?php
    // Handle return request
    if(isset($_POST['submit_feedback'])) {

        $member_id = $_POST['member_id'];
        $toy_id = $_POST['toy_id'];
        $rating = $_POST['rating'];
        $ease_of_use_rating = $_POST['ease_of_use_rating'];
        $toy_condition_rating = $_POST['toy_condition_rating'];
        $overall_satisfaction_rating = $_POST['overall_satisfaction_rating'];
        $comments = $_POST['comments'];
        $improvement_suggestions = $_POST['improvement_suggestions'];

        // Insert feedback data into the database
        $query = $con->prepare("INSERT INTO feedbacks (member_id, toy_id, rating, ease_of_use_rating, toy_condition_rating, overall_satisfaction_rating, comments, improvement_suggestions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("iiiiisss", $member_id, $toy_id, $rating, $ease_of_use_rating, $toy_condition_rating, $overall_satisfaction_rating, $comments, $improvement_suggestions);
     
        if ($query->execute()) {
            // Feedback inserted successfully
            echo "<script>alert('Feedback submitted successfully');</script>";
        } else {
            // Error occurred while inserting feedback
            echo "<script>alert('Failed to submit feedback');</script>";
        }
        
            $con->close();
            header("Location: returned_toys.php");
            exit();
        
    }
    ob_end_flush();
?>

</body>
</html>
