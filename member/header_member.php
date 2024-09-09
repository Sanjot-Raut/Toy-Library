<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #cd-logo img {
            width: auto;
            height: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }

        #cd-logo p {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            display: inline;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            display: inline;
            margin-left: 20px;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-links li a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <div id="cd-logo">
            <a >
                <img src="../img/kidstoys.png" alt="Logo" />
                <p>Toy Library</p>
            </a>
        </div>
        
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Profile</a></li>
                <li><a href="my_toys.php">Borrowed Toys</a></li>
                <li><a href="available_toys.php">Available Toys</a></li>
                <li><a href="returned_toys.php">Returned Toys and Feedback</a></li>
                <li><a href="../logout.php" >Logout</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
