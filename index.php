<?php
    require "db_connect.php";
    require "header.php";
    require "footer.php";
    session_start();
    
    if(empty($_SESSION['type']));
    else if(strcmp($_SESSION['type'], "librarian") == 0)
        header("Location: librarian/home.php");
    else if(strcmp($_SESSION['type'], "member") == 0)
        header("Location: member/home.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Library</title>
    <link rel="stylesheet" type="text/css" href="css/index_style.css" />
    <style>

         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
            background-image: url('img/bg1.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
       
        #allTheThings {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 70vh; /* Adjusted */
            margin-top: 50px; /* Adjusted */
        }
        #member, #librarian {
            text-align: center;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        #member:hover, #librarian:hover {
            transform: translateY(-10px);
        }
        #member img, #librarian img {
            width: 200px;
            height: auto;
            border-radius: 10px;
        }
        #verticalLine {
            border-left: 2px solid #ccc;
            height: 250px; /* Adjusted */
            margin: 0 20px;
        }
        #librarian-link ,#member-link{
            color: #333;
            text-decoration: none;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="allTheThings">
        <div id="member">
            <a id="member-link" href="member">
                <img src="img/member.jpg" alt="Member Login" /><br />
                Member Login
            </a>
        </div>
        <div id="verticalLine"></div>
        <div id="librarian">
            <a id="librarian-link" href="librarian">
                <img src="img/librarian.jpg" alt="Librarian Login" /><br />
                Librarian Login
            </a>
        </div>
    </div>
   
</body>
</html>
