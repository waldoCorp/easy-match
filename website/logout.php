<?php
// Page to logout a user:
session_regenerate_id(true); // Create new ID to be safe
$_SESSION = array(); // Unset all session variables
header('Location: ./index.php');

