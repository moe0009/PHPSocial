<?php
// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.php");
    exit;
}

// Include the database connection file
require_once 'db_connection.php';

// Retrieve the posts data
$sql = "SELECT posts.*, users.board_username, users.userid, COUNT(post_likes.post_id) as post_likes_count 
        FROM posts 
        JOIN users ON posts.userid = users.userid 
        LEFT JOIN post_likes ON posts.id = post_likes.post_id 
        GROUP BY posts.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
$posts = $result->fetch_all(MYSQLI_ASSOC);

// Function to retrieve comments for a post
function getComments($conn, $post_id) {
    $sql = "SELECT comments.*, users.board_username, COUNT(comment_likes.comment_id) as comment_likes_count 
            FROM comments 
            JOIN users ON comments.userid = users.userid 
            LEFT JOIN comment_likes ON comments.id = comment_likes.comment_id 
            WHERE comments.post_id = ? 
            GROUP BY comments.id 
            ORDER BY comments.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to check if a user has liked a post
function hasUserLikedPost($conn, $user_id, $post_id) {
    $sql = "SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to check if a user has liked a comment
function hasUserLikedComment($conn, $user_id, $comment_id) {
    $sql = "SELECT * FROM comment_likes WHERE user_id = ? AND comment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Retrieve the user's board_username
$sql = "SELECT board_username FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Dashboard</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <!-- Add your custom CSS styles here -->
  <style>
    /* Add your styles here */
    .profile_img {
      border-radius: 50%;
    }
    #postForm{
      position: relative;
      left: 318px;
    }
    .post, .comment {
      border: 1px solid #ccc;
      padding: 15px;
      margin-bottom: 20px;
    }
    .post-header {
      display: flex;
      align-items: center;
    }
    .post-header img {
      margin-right: 10px;
    }
    .post-header .username {
      font-weight: bold;
    }
    button {
      background-color: #4CAF50; /* Green */
      border: none;
      color: white;
      padding: 5px 10px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 10px;
    }
.logout-btn{
  position: relative;
  left: 600px;
  color: red;
  text-decoration: none;
  border: 1px solid red;
  width: 90px;
  padding: 5px;
  border-radius: 5px;
}
  </style>
</head>
<body>
  <div class="container">
    <div id="profile_info" class="top-right">
      <img class="profile_img" src="./images/profile.jpg" alt="Profile Picture" width="50" height="50">
      <span><?php echo $user_data['board_username']; ?></span>
      <a class="logout-btn" href="logout.php">Log Out</a>
       
    </div>
    <h1>Dashboard</h1>
    <form id="postForm" action="create_post.php" method="post">
      <label for="postTitle">Subject:</label>
      <input type="text" id="postTitle" name="postTitle" required>
      <label for="postBody">Body:</label>
      <textarea id="postBody" name="postBody" required></textarea>
      <input type="submit" value="Post" class="button">
    </form>
    <?php foreach ($posts as $post): ?>
      <div class="post">
        <div class="post-header">
          <img class="profile_img" src="./images/profile.jpg" alt="Profile Picture" width="50" height="50">
          <span class="username"><?php echo $post['board_username']; ?></span>
        </div>
        <h2><?php echo $post['title']; ?></h2>
        <p><?php echo $post['body']; ?></p>
        <p>Posted by <?php echo $post['board_username']; ?> on <?php echo $post['created_at']; ?></p>
        <form action="post_likes_handler.php" method="post">
          <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
          <button type="submit" name="toggle_like" class="button"><?php echo hasUserLikedPost($conn, $_SESSION['user_id'], $post['id']) ? 'Unlike' : 'Like'; ?></button>
        </form>
        <span><?php echo $post['post_likes_count']; ?> likes</span>
        <h3>Comments</h3>
        <?php $comments = getComments($conn, $post['id']); ?>
        <?php foreach ($comments as $comment): ?>
          <div class="comment">
            <p><?php echo $comment['body']; ?></p>
            <p>Commented by             <?php echo $comment['board_username']; ?> on <?php echo $comment['created_at']; ?></p>
            <form action="comment_likes_handler.php" method="post">
              <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
              <button type="submit" name="toggle_like" class="button"><?php echo hasUserLikedComment($conn, $_SESSION['user_id'], $comment['id']) ? 'Unlike' : 'Like'; ?></button>
            </form>
            <span><?php echo $comment['comment_likes_count']; ?> likes</span>
          </div>
        <?php endforeach; ?>
        <form action="comment_handler.php" method="post">
          <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
          <textarea name="comment_body" placeholder="Write a comment..."></textarea>
          <button type="submit" name="submit_comment" class="button">Post Comment</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

