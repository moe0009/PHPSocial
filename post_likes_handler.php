<?php
session_start();
require_once 'db_connection.php';

if (isset($_POST['toggle_like'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    // Check if the user has already liked the post
    $sql = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a like exists, remove it
        $sql = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $post_id);
        $result = $stmt->execute();
    } else {
        // If no like exists, add a new like
        $sql = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $post_id);
        $result = $stmt->execute();
    }

    if ($result) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
