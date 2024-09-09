<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Toy Library</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
		<link rel="stylesheet" href="css/insert_toy_style.css">
	</head>
	<style>
    .cd-form {
        width: 500px;
        margin: 0 auto;
        padding: 30px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .cd-form legend {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .cd-form .icon {
        position: relative;
        margin-bottom: 20px;
    }

    .cd-form input[type="text"],
    .cd-form input[type="number"],
    .cd-form textarea,
    .cd-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .cd-form input[type="file"] {
        padding: 10px 0;
    }

    .cd-form input[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .cd-form input[type="submit"]:hover {
        background-color: #45a049;
    }

    .error-message {
        color: #FF0000;
        margin-bottom: 20px;
    }

    .error-message p {
        margin: 0;
    }
</style>
	<body>
		<form class="cd-form" method="POST" action="#" enctype="multipart/form-data">
			<center><legend>Add New Toy Details</legend></center>
			
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
			<div class="icon">
				<input class="t-name" type="text" name="t_name" placeholder="Toy Name" required />
			</div>
			
			<div class="icon">
				<textarea class="t-description" name="t_description" placeholder="Toy Description" required></textarea>
			</div>
			
			<div class="icon">
				<select class="t-category" name="t_category" required>
					<option value="">Select Category</option>
					<option value="Action Figures">Action Figures</option>
					<option value="Dolls">Dolls</option>
					<option value="Building Blocks">Building Blocks</option>
					<option value="Board Games">Board Games</option>
					<option value="Educational Toys">Educational Toys</option>
					<option value="Puzzles">Puzzles</option>
					<option value="Art and Craft">Art and Craft</option>
					<option value="Outdoor Toys">Outdoor Toys</option>
					<option value="Plush Toys">Plush Toys</option>
					<option value="Remote Control Toys">Remote Control Toys</option>
					<option value="Vehicle Toys">Vehicle Toys</option>
					<option value="Musical Toys">Musical Toys</option>
				</select>
			</div>
			
			<div class="icon">
				<input class="t-quantity" type="number" name="t_quantity" placeholder="Total Quantity" required />
			</div>

			

			<div class="icon">
				<input class="t-image" type="file" name="t_image" accept="image/*" required />
			</div>
			
			<div class="icon">
				<select class="t-type" name="t_type" required>
					<option value="">Select Type</option>
					<option value="Regular">Regular</option>
					<option value="Premium">Premium</option>
				</select>
			</div>
			
			<br />
			<input class="t-submit" type="submit" name="t_add" value="Add Toy" />
		</form>
		
		<?php
			if(isset($_POST['t_add']))
			{
				$t_name = $_POST['t_name'];
				$t_description = $_POST['t_description'];
				$t_category = $_POST['t_category'];
				$t_quantity = $_POST['t_quantity'];
				$t_image = $_FILES['t_image']['name'];
				$t_image_tmp = $_FILES['t_image']['tmp_name'];
				$t_type = $_POST['t_type'];

				move_uploaded_file($t_image_tmp, "../toys_images/$t_image");

				$query = $con->prepare("INSERT INTO toys (toy_name, description, category, quantity, available_quantity, image_url, type) VALUES (?, ?, ?, ?, ?, ?, ?);");
				$query->bind_param("sssiiss", $t_name, $t_description, $t_category, $t_quantity, $t_quantity, $t_image, $t_type);
				
				if(!$query->execute())
					die(error_without_field("ERROR: Couldn't add toy"));
				echo success("New toy record has been added");
			}
		?>
	</body>
</html>
