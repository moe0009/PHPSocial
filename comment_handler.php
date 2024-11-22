<?php
session_start();
require_once 'db_connection.php';

if (isset($_POST['submit_comment'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment_body = $_POST['comment_body'];
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO comments (userid, post_id, body, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $user_id, $post_id, $comment_body, $created_at);
    $result = $stmt->execute();

    if ($result) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
