<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "verify_member.php";
    require "header_member.php";

    // Fetch member details from the database
    $query = $con->prepare("SELECT * FROM members WHERE username = ?;");
    $query->bind_param("s", $_SESSION['username']);
    $query->execute();
    $result = $query->get_result();
    $member = $result->fetch_assoc();

    $query = $con->prepare("SELECT * FROM membership_details WHERE member_id = ? order  by id desc limit 1;");
    $query->bind_param("s", $_SESSION['id']);
    $query->execute();
    $result = $query->get_result();
    $membership = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Toy Library</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="css/home_style.css">
    <link rel="stylesheet" type="text/css" href="../css/custom_radio_button_style.css">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            width: 100%;
            max-width: 800px; /* Adjust the max-width as needed */
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

         .header {
            background-color: #ee5253; /* Red color for header */
            color: #fff;
            padding: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header img {
            width: 80px; /* Adjust the size of the logo */
            height: auto;
            margin-right: 10px; /* Add space between logo and library name */
        }

        .header h3 {
            margin: 0;
        }

        .profile-pic {
            float: left;
            width: 40%;
            padding: 20px;
        }

        .profile-pic img {
            width: 100%;
            border-radius: 50%;
        }

        .profile-info {
            float: right;
            width: 60%;
            padding: 20px;
            text-align: left;
        }

        .profile-info h4 {
            margin-top: 0;
        }

        .profile-details p {
            margin: 5px 0;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <img src="../img/kidstoys.png" alt="Toy Library Logo">
                <h3>Toy Library card</h3>
            </div>
            <div class="profile-pic">
                <?php
                    // Check if profile picture is set
                    if (!empty($member['profile_picture'])) {
                        echo '<img src="'. $member['profile_picture'] .'" />';
                    } else {
                        echo '<img src="../img/default_profile_pic.jpg" alt="Default Profile Picture" />';
                    }
                ?>
            </div>
            <div class="profile-info">
                <h4>Member Details</h4>
                <div class="profile-details">
                    <p><b>ID :</b> <?php echo $member['id']; ?></p>
                    <p><b>Username:</b> <?php echo $member['username']; ?></p>
                    <p><b>Full Name:</b> <?php echo $member['full_name']; ?></p>
                    <p><b>Email:</b> <?php echo $member['email']; ?></p>
                    <p><b>Date of Birth:</b> <?php echo $member['date_of_birth']; ?></p>
                    <p><b>Address:</b> <?php echo $member['address']; ?></p>
                    <p><b>Contact Number:</b> <?php echo $member['contact_number']; ?></p>
                    <p><b>Deposit Amount :</b> <?php echo (int)$member['deposit']; ?></p>
                    <h4>Membership Details</h4>
                    <p><b>Membership Status:</b> <?php echo $membership['status']; ?></p>
                    <p><b>Membership Type:</b> <?php echo $membership['type']; ?></p>
                    
                    <p><b>Membership Days Remaining :</b> <?php $remainingDays = (new DateTime($membership['end_date']))->diff(new DateTime())->days;echo $remainingDays; ?> Days</p>
                    <p><b>Account Status :</b> <?php
                    if ($member['deposit'] > 0) 
                     echo "<span style='color: green;'>Active</span>";
                    elseif ($member['deposit'] == 0)
                      echo "<span style='color:blue'>Inactive You have left the Library</span>";
                    else
                      echo "<span style='color: red;'>Blocked ! You are removed from library and deposit have deducted as you have not returned the Toy.</span>";
                     ?></p>
                </div>
            </div>
        </div>
    </div>
    <footer>
        &copy; 2024 Toy Library. All rights reserved.
    </footer>
</body>
</html>
