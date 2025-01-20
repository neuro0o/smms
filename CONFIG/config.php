<?php
    $databaseHost = 'localhost';
    $databaseUsername = 'root';
    $databasePassword = '';
    $databaseName = 'smms';
    $url = "http://localhost/smms";

    // define ADMIN_BASE_URL if it hasn't been defined yet
    if (!defined('ADMIN_BASE_URL')) {
        define('ADMIN_BASE_URL', 'http://localhost/smms/MODULES/ADMIN_MODULE');
    }

    // define BASE_URL if it hasn't been defined yet
    if (!defined('BASE_URL')) {
        define('BASE_URL', 'http://localhost/smms');
    }

    // define BASE_PATH if it hasn't been defined yet
    if (!defined('BASE_PATH')) {
        define('BASE_PATH', __DIR__);
    }

    // create a connection to the database
    $conn = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

    // check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    // echo "DB Connection Successful." . "<br>";
?>