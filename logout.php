<?php
// Start session
session_start();

// Unset all session variables
$_SESSION['isLoggedIn'] = false;
$_SESSION['message'] = 'You have successfully logged out.';

// Store the previous page URL if available
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
// Redirect the user back to the previous page or a default page if HTTP_REFERER is not set
if (strpos($redirect_url, 'profile.php') === true) {
    // If it does, redirect to index.php
    $redirect_url = 'index.php';
}
header("Location: $redirect_url");
exit();
