<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "bootcamp6";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>