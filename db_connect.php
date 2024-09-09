<?php
    // Establishing a connection to the toy library database
    $con = mysqli_connect('localhost', 'root', '', 'toy_library');

    // Checking if the connection was successful
    if (!$con) {
        die("ERROR: Couldn't connect to the database");
    }
?>
