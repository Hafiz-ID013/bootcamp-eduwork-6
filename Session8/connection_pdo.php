<?php
    $host = "localhost";
    $usernam = "root";
    $password = "";
    $database = "bootcamp6";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $usernam, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>