<?php
// Set up database connection
$hostname = "localhost";
$username = "not available";
$password = "not available";
$dbname = "not available";

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
