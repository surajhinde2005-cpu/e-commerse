<?php
session_start();

/* Unset all session data */
$_SESSION = [];

/* Destroy session */
session_destroy();

/* Prevent back button cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* Redirect to login */
header("Location: userlogin.php");
exit;
