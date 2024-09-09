<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../verify_logged_out.php";
	require "../header.php";
	require "../footer.php";
?>

<!DOCTYPE html>	
<html>
	<head>
		 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Login - Toy Library</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/login_styles.css">
	<style>
		 
	</style>
	</head>
	<body>
        <div class="login-container">
		    <form class="login-form cd-form" method="POST" action="#">
		
		    <h2>Member Login</h2>
			
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
			<div class="input-group">
				<input class="m-user" type="text" name="m_user" placeholder="Username" required />
			</div>
			
			<div class="input-group">
				<input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
			</div>
			
			<button type="submit" name="m_login">Login</button>
			
			<br /><br /><br /><br />
			
			<p align="center">Don't have an account?&nbsp;<a href="register.php" style="text-decoration:none; color:red;">Register Now!</a>

			<p align="center"><a href="../index.php" style="text-decoration:none;">Go Back</a>
		</form>
</div >

</body> 
    </html>
	
	<?php
		if(isset($_POST['m_login']))
		{	$user=$_POST['m_user'];
			$pass=sha1($_POST['m_pass']);
			$query = $con->prepare("SELECT id FROM members WHERE username = ? AND password = ?;");
			$query->bind_param("ss",$user,$pass );
			$query->execute();
			$result = $query->get_result();
			
			if(mysqli_num_rows($result) != 1)
				echo error_without_field("Invalid details or Account has not been activated yet!");
			else 
			{
				$resultRow = mysqli_fetch_array($result);
				$balance = $resultRow[1];
			if($balance < 0){
				echo error_without_field("Your account has been suspended. Please contact librarian for further information!");
			} else {
					$_SESSION['type'] = "member";
					$_SESSION['id'] = $resultRow[0];
					$_SESSION['username'] = $_POST['m_user'];
					header('Location: home.php');
				}
			}
		}
	?>
	