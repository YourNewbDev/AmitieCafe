// Login loading spinner
document.getElementById("loginForm").onsubmit = function () {
    // Hide the login button and show the spinner
    document.getElementById("loginButton").style.display = "none";
    document.getElementById("loadingSpinner").style.display = "block";
}