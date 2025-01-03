<?php
// Database configuration
$host = "localhost"; // Replace with your database host if not localhost
$username = "root";  // Replace with your MySQL username
$password = "";      // Replace with your MySQL password
$database = "eduflow"; // Database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful
echo "Connected successfully to the database.";
?>
