<?php
error_reporting(E_ALL);
ini_set("display_errors",1);


// Endpoint for user creation, login, logout, and name update

require_once $function_path . 'check_email.php';
require_once $function_path . 'password_check.php';
require_once $function_path . 'add_new_user.php';
require_once $function_path . 'get_uuid.php';
require_once $function_path . 'send_password_link.php';
require_once $function_path . 'update_password.php';
require_once $function_path . 'update_last_login.php';


if( empty($_SESSION['uuid']) ) {
	$_SESSION['login'] = false; // We are not logged in yet
	$email = strtolower($_POST['email']);
} else {
	$email = $_SESSION['email'];
	$uuid = $_SESSION['uuid'];
}


include '/srv/nameServer/functions.php/spam_prevention_script.php';


// Request for password-setting link:
if ( $_POST["type"] == 0) {

	// Check for email validity:
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		exit("Please use a valid email address\n");
	}


	// Now that we know we're good to add the user, do so:
	$s = send_password_link($email);

	// And re-direct back to homepage:
	header('Location: ../index.php');


} elseif ( $_POST["type"] == 1 ) { // Existing user
	$s = password_check($email,$_POST["passwd"]);
	if ($s) {
		//header('Location: /path/to/creation/success.php');

		// Set Session variables so we don't need to keep hitting DB:
		$_SESSION['login'] = true; // We are now logged in
		$_SESSION['email'] = $email; // Set stuff here
		$_SESSION['uuid'] = get_uuid($email);
		// GET PARTNER(S) HERE AND SET IF ONLY ONE

		// Update last_login time:
		update_last_login($_SESSION['uuid']);

		header('Location: ../show_names.php');


	} else {
		//header('Location: /path/to/failed/login.php');
		echo "Login FAILURE!";

	}

} elseif ( $_POST["type"] == 2 ) { // Logout Request

	session_regenerate_id(true); // Create new ID to be safe
	$_SESSION = array(); // Unset all session variables
	header('Location: ./index.php');

} elseif ( $_POST["type"] == 3 ) { // Password Change Request

	$passwd1 = $_POST['passwd1'];
	$passwd2 = $_POST['passwd2'];

	// Check to make sure passwords match:
	if ($passwd1 !== $passwd2 || "" == trim(($_POST['passwd1']))) {
		exit("Passwords do not match or are invalid \n");
	}


	// Make sure this is a legitimate request:
	if( $_SESSION['pass_reset'] ) {
		// Now that we know we're good to update the password, do so:
		$uuid = $_SESSION['update_uuid'];
		update_password($uuid,$passwd1);

		// Clear SESSION variables to stop weird mailicious things:
		$_SESSION = array();
		header('Location: ../index.php');
	} else {
		// This was bad somehow:
		header('Location: ../password_timeout.php');
	}


} else {
	// We got here on accident somehow
	header('HTTP/1.0 400 Bad Request');
}
