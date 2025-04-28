<?php
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the screen

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'J2s3jAsd?';
$db_name = 'test_IFT3225';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");
?>
