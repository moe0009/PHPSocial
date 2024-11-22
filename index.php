<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>replit</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
 <!-- login page -->
<div class="container">
  <h1>Login</h1>
  <form id="loginForm" action="login.php" method="POST">
    <label for="loginEmail">Email:</label>
    <input type="email" id="loginEmail" name="email" required>
    <label for="loginPassword">Password:</label>
    <input type="password" id="loginPassword" name="password" required>
    <input type="submit" value="Login">
  </form>
  <p class="switch">Don't have an account? <a href="#" id="signupLink">Sign Up</a></p>
</div>
<!-- sign up page -->
<div class="container hidden" id="signupContainer">
  <h1>Sign Up</h1>
 <form id="signupForm" action="register.php" method="POST">
  <label for="signupUsername">Username:</label>
  <input type="text" id="signupUsername" name="signupUsername" required>
  <label for="signupEmail">Email:</label>
  <input type="email" id="signupEmail" name="signupEmail" required>
  <label for="signupPassword">Password:</label>
  <input type="password" id="signupPassword" name="signupPassword" required>
  <input type="submit" value="Sign Up">
</form>

  <p class="switch">Already have an account? <a href="#" id="loginLink">Log In</a></p>
</div>

 


  
</body>
</html>

  <script src="script.js"></script>

  <!--
  This script places a badge on your repl's full-browser view back to your repl's cover
  page. Try various colors for the theme: dark, light, red, orange, yellow, lime, green,
  teal, blue, blurple, magenta, pink!
  -->
  <script src="https://replit.com/public/js/replit-badge-v2.js" theme="dark" position="bottom-right"></script>
</body>

</html>