<?php
// Include the database connection file
require_once 'db_connection.php';

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Search for a user with the provided email and password
    $sql = "SELECT userid, board_username, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, start a session and store user data
        session_start();
        $_SESSION['user_id'] = $user['userid'];
        $_SESSION['board_username'] = $user['board_username'];
        $_SESSION['email'] = $user['email'];

        // Redirect to the dashboard
        header("Location: dashboard.php");
    } else {
        // Invalid email or password
        echo "Invalid email or password.";
    }
}

$conn->close();
?>
