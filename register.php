<?php
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form is submitted via POST method
    $board_username = $_POST["signupUsername"];
    $email = $_POST["signupEmail"];
    $password = $_POST["signupPassword"];

    // Check if the user already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "User with this email already exists.";
    } else {
        // Insert the new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (board_username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $board_username, $email, $hashed_password);

        // Debugging print
        echo "Connection established and about to execute the query.<br>";

        if ($stmt->execute()) {
            // Registration successful
            echo "User registered successfully.";
            // Redirect to the login page
            header("Location: index.php");
        } else {
            // Error while registering
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$conn->close();
?>
