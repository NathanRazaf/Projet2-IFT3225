<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';  // or 'root'
$db_pass = 'J2s3jAsd?';  // the password you set
$db_name = 'test_IFT3225';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");
