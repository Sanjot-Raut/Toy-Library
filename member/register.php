<?php
require "../db_connect.php";
require "../message_display.php";
require "../header.php";

function validatePassword($password)
{
	if (strlen($password) < 8 || !preg_match("/[0-9]+/", $password) || !preg_match("/[a-zA-Z]+/", $password)) {
		return false;
	}
	return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Define validation flags
	$isValid = true;
	$errors = [];

	// Validate Full Name
	if (empty($_POST["m_name"])) {
		$isValid = false;
		$errors["m_name"] = "Full Name is required";
	}

	// Validate Email
	if (empty($_POST["m_email"])) {
		$isValid = false;
		$errors["m_email"] = "Email is required";
	} elseif (!filter_var($_POST["m_email"], FILTER_VALIDATE_EMAIL)) {
		$isValid = false;
		$errors["m_email"] = "Invalid email format";
	}

	// Validate Username
	if (empty($_POST["m_user"])) {
		$isValid = false;
		$errors["m_user"] = "Username is required";
	}

	// Validate Password
	if (empty($_POST["m_pass"])) {
		$isValid = false;
		$errors["m_pass"] = "Password is required";
	} elseif (!validatePassword($_POST["m_pass"])) {
		$isValid = false;
		$errors["m_pass"] = "Password must be at least 8 characters long and contain at least one number and one letter";
	}

	// Validate Date of Birth
	if (empty($_POST["m_dob"])) {
		$isValid = false;
		$errors["m_dob"] = "Date of Birth is required";
	}

	// Validate Address
	if (empty($_POST["m_address"])) {
		$isValid = false;
		$errors["m_address"] = "Address is required";
	}

	// Validate Contact Number
	if (empty($_POST["m_contact"])) {
		$isValid = false;
		$errors["m_contact"] = "Contact Number is required";
	}

	// Validate Membership Type
	if (empty($_POST["m_type"])) {
		$isValid = false;
		$errors["m_type"] = "Membership Type is required";
	}

	// Validate Membership Duration
	if (empty($_POST["m_duration"])) {
		$isValid = false;
		$errors["m_duration"] = "Membership Duration is required";
	}

	// Check if there are no errors, then proceed with registration
	if ($isValid) {
		// Process registration
		// ...
	} else {
		// Display errors to the user
		echo "<div class='error-message'>";
		foreach ($errors as $error) {
			echo "<p>$error</p>";
		}
		echo "</div>";
	}
}
?>

<html>

<head>
	<title>LMS</title>
	<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
	<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
	<link rel="stylesheet" href="css/register_style.css">
	<style>
		/* Global Styles */
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f4f4f4;
		}

		.container {
			max-width: 500px;
			margin: 50px auto;
			padding: 20px;
			background-color: #fff;
			border-radius: 5px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		h2 {
			text-align: center;
			margin-bottom: 20px;
		}

		.cd-form {
			display: flex;
			flex-direction: column;
		}

		.icon {
			margin-bottom: 20px;
		}

		label {
			font-weight: bold;
			color: #333;
			margin-bottom: 5px;
		}

		input[type="text"],
		input[type="email"],
		input[type="password"],
		select {
			width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			box-sizing: border-box;
		}

		select {
			appearance: none;
			-webkit-appearance: none;
			-moz-appearance: none;
			background-image: url('data:image/svg+xml;utf8,<svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 12l-6-6h12l-6 6z"/></svg>');
			background-repeat: no-repeat;
			background-position-x: 95%;
			background-position-y: center;
		}

		input[type="submit"] {
			width: 100%;
			padding: 10px;
			background-color: #333;
			color: #fff;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}

		input[type="submit"]:hover {
			background-color: #555;
		}

		.error-message {
			color: #f00;
			margin-bottom: 10px;
		}

		.go-back-link {
			text-align: center;
			margin-top: 20px;
		}

		.go-back-link a {
			text-decoration: none;
			color: #666;
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
	<form class="cd-form" method="POST" action="#" enctype="multipart/form-data">
		<center>
			<legend>Member Registration</legend>
			<p>Please fill up the form below:</p>
		</center>
		<div class="error-message" id="error-message">
			<p id="error"></p>
		</div>
		<div class="icon">
			<label for="m_name">Full Name</label>
			<input class="m-name" type="text" name="m_name" id="m_name" placeholder="Full Name" required />
		</div>
		<div class="icon">
			<label for="m_email">Email</label>
			<input class="m-email" type="email" name="m_email" id="m_email" placeholder="Email" required />
		</div>
		<div class="icon">
			<label for="m_user">Username</label>
			<input class="m-user" type="text" name="m_user" id="m_user" placeholder="Username" required />
		</div>
		<div class="icon">
			<label for="m_pass">Password</label>
			<input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
		</div>
		<div class="icon">
			<label for="m_dob">Date Of Birth</label>
			<input class="m-dob" type="date" name="m_dob" placeholder="Date Of Birth" required />
		</div>
		<div class="icon">
			<label for="m_address">Address</label>
			<input class="m-address" type="text" name="m_address" id="m_address" placeholder="Address" required />
		</div>
		<div class="icon">
			<label for="m_contact">Contact Number</label>
			<input class="m-contact" type="text" name="m_contact" id="m_contact" placeholder="Contact Number" required />
		</div>
		<div class="icon">
			<label for="m_type">Membership Type</label>
			<select class="m-type" name="m_type" id="m_type" required>
				<option value="" disabled selected>Select Membership Type</option>
				<option value="Regular">Regular</option>
				<option value="Premium">Premium</option>
			</select>
		</div>
		<div class="icon">
			<label for="m_duration">Membership Duration</label>
			<select class="m-duration" name="m_duration" id="m_duration" required>
				<option value="" disabled selected>Select Membership Duration</option>
				<option value="1">1 Month</option>
				<option value="3">3 Months</option>
				<option value="6">6 Months</option>
				<option value="12">1 Year</option>
			</select>
		</div>
		<div class="icon">
			<label for="m_picture">Profile Picture</label>
			<input class="m-picture" type="file" name="m_picture" id="m_picture" accept="image/*" required />
		</div>
		<br />
		<input type="submit" name="m_register" value="Submit" />
	</form>
</body>

<?php
if (isset($_POST['m_register'])) {
	// Validate membership duration
	if ($_POST['m_duration'] < 1) {
		echo error_with_field("Membership duration must be at least 1 month", "m_duration");
	} else {
		$password = $_POST['m_pass'];
		if (!validatePassword($password)) {
			echo error_with_field("Password must be at least 8 characters long and contain at least one number and one letter", "m_pass");
		} else {
			$query = $con->prepare("(SELECT username FROM members WHERE username = ?) UNION (SELECT username FROM pending_registrations WHERE username = ?);");
			$query->bind_param("ss", $_POST['m_user'], $_POST['m_user']);
			if (!$query->execute()) {
				echo error_without_field("Error executing query: " . $query->error);
			} else {
				if (mysqli_num_rows($query->get_result()) != 0) {
					echo error_with_field("The username you entered is already taken", "m_user");
				} else {
					$query = $con->prepare("(SELECT email FROM members WHERE email = ?) UNION (SELECT email FROM pending_registrations WHERE email = ?);");
					$query->bind_param("ss", $_POST['m_email'], $_POST['m_email']);
					if (!$query->execute()) {
						echo error_without_field("Error executing query: " . $query->error);
					} else {
						if (mysqli_num_rows($query->get_result()) != 0) {
							echo error_with_field("An account is already registered with that email", "m_email");
						} else { // Check if the file was uploaded without errors
							if (isset($_FILES['m_picture']) && $_FILES['m_picture']['error'] === UPLOAD_ERR_OK) {
								$uploadDirectory = "/Toy Library/member/profile_pic/";
								$fileName = basename($_FILES['m_picture']['name']);
								$filePath1 = $_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $fileName;
								$filePath = str_replace("M:/Server/Xamp/htdocs/Toy Library/member", ".", $filePath1);


								$filePath = str_replace('\\', '/', $filePath);

								var_dump($_FILES['m_picture']);
								var_dump($filePath);


								// Move the uploaded file to the specified directory
								if (move_uploaded_file($_FILES['m_picture']['tmp_name'], $filePath)) {
									// Insert registration data into the database

									$query = $con->prepare("INSERT INTO pending_registrations(username, password, full_name, email,date_of_birth, address, contact_number, membership_type, membership_duration, profile_picture) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
									if (!$query) {
										// Handle SQL error
										echo "Error: " . $con->error;
									} else {
										// Proceed with binding parameters and executing query
										$query->bind_param("ssssssisss", $_POST['m_user'], sha1($_POST['m_pass']), $_POST['m_name'], $_POST['m_email'], $_POST['m_dob'], $_POST['m_address'], $_POST['m_contact'], $_POST['m_type'], $_POST['m_duration'], $filePath);

										if ($query->execute()) {
											echo success("Details submitted, you will be notified after verification.");
											echo "Membership Type: " . $_POST['m_type'];
										} else {
											echo error_without_field("Couldn't record details. Please try again later.");
										}
									}
								} else {
									echo error_without_field("Failed to upload image.");
								}
							} else {
								// Handle file upload errors
								echo error_without_field("Error uploading file. Please try again.");
							}
						}
					}
				}
			}
		}
	}
}
?>
<footer>
	&copy; 2024 Toy Library. All rights reserved.
</footer>

</html>