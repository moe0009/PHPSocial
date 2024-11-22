<?php
// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Include the database connection file
require_once 'db_connection.php';

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['postTitle'];
    $body = $_POST['postBody'];
    $user_id = $_SESSION['user_id'];

    // Insert the new post
$sql = "INSERT INTO posts (userid, title, body, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("iss", $user_id, $title, $body);

    if ($stmt->execute()) {
        // Post created successfully
        header("Location: dashboard.php");
    } else {
        // Error while creating post
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
