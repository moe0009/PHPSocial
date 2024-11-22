document.getElementById("signupLink").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("loginForm").reset();
  document.getElementById("signupContainer").classList.remove("hidden");




  // Function to handle logout
function handleLogout() {
  loggedIn = false;
  window.location.href = "login.html"; // Redirect to login page
}

// Event listener for logout button
document.getElementById("logoutButton").addEventListener("click", handleLogout);

});
