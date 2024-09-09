<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "../verify_logged_out.php";
    require "../header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Login - Toy Library</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/login_styles.css">
    <style>
            footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style> <!-- Reusing the login_styles.css for consistency -->
</head>
<body>
    <div class="login-container">
        <form class="login-form cd-form" method="POST" action="#">
            <h2>Librarian Login</h2>

            <!-- Error message display -->
            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>
            
            <!-- Input fields for username and password -->
            <div class="input-group">
                <input class="l-user" type="text" name="l_user" placeholder="Username" required>
            </div>
            
            <div class="input-group">
                <input class="l-pass" type="password" name="l_pass" placeholder="Password" required>
            </div>
            
            <!-- Login button -->
            <button type="submit" name="l_login">Login</button>
            
            <!-- Go back link -->
            <p class="go-back-link"><a href="../index.php">Go Back</a></p>
        </form>
    </div>
</body><footer>
        &copy; 2024 Toy Library. All rights reserved.
    </footer>
</html>

<?php
    if(isset($_POST['l_login']))
    {
        $query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
        $u=$_POST['l_user'];
        $p=sha1($_POST['l_pass']);
        $query->bind_param("ss", $u, $p);
        $query->execute();
        $result = $query->get_result(); // Fetch the result after executing the query
        if(mysqli_num_rows($result) != 1) // Use the fetched result here
            echo error_without_field("Invalid username/password combination");
        else
        {
            $_SESSION['type'] = "librarian";
            $_SESSION['id'] = mysqli_fetch_array($result)[0];
            $_SESSION['username'] = $_POST['l_user'];
            header('Location: home.php');
        }
    }
?>

