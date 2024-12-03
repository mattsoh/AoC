<?php
// public/logout.php
session_start(); // Start the session to manage user session data

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();
session_start(); 

// Output HTML with JavaScript to prevent back navigation
echo '<html><head><meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"><meta http-equiv="Pragma" content="no-cache"><meta http-equiv="Expires" content="0"></head><body>';
echo '<script type="text/javascript">';
echo 'history.pushState(null, null, location.href);';
echo 'window.addEventListener("popstate", function(event) { history.pushState(null, null, location.href); });';
echo 'setTimeout(function() {';
echo 'window.location.replace("login.php");';
echo '}, 0);';
echo '</script>';
echo '</body></html>';
exit;
?>
