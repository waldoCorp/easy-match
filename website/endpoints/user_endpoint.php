<?php
// Endpoint for user creation, login, logout, and name update

require_once '/srv/nameServer/functions.php/check_email.php';
require_once '/srv/nameServer/functions.php/add_new_user.php';
require_once '/srv/nameServer/functions.php/password_check.php';

//$user_table = $master_user_table;
$user_table = 'users';


if( !$_SESSION['email'] ) {
	$_SESSION['login'] = false; // We are not logged in yet
	$email = $_POST['email'];
}


include '/srv/nameServer/functions.php/spam_prevention_script.php';


// If "type" is 0 -> this is a new user
if ( $_POST["type"] == 0) {

	$passwd1 = $_POST['passwd1'];
	$passwd2 = $_POST['passwd2'];

	// Check to make sure passwords match:
	if ($passwd1 !== $passwd2 || "" == trim(($_POST['passwd1']))) {
		exit("Passwords do not match or are invalid \n");
	}


	// Make sure username doesn't exist yet:
	if (check_email($user_table,$email)) {
		// Maybe don't say this as it makes it easier to spear-phish
		exit("Username is not unique\n");
	}

	// Check for email validity:
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		exit("Please use a valid email address\n");
	}


	// Now that we know we're good to add the user, do so:
	$s = add_new_user($user_table,$email,$passwd1,$firstName,$lastName);

	// If success, redirect to the appropriate page:
	if ($s) {
		//header('Location: /path/to/creation/success.php');
		echo "New user added with:<br>";
		echo '<pre>' , var_export($_POST) , '</pre>';
	}

} elseif ( $_POST["type"] == 1 ) { // Existing user
	$s = password_check($user_table,$email,$_POST["passwd"]);
	if ($s) {
		//header('Location: /path/to/creation/success.php');

		// Set Session variables so we don't need to keep hitting DB:
		$_SESSION['login'] = true; // We are now logged in
		$_SESSION['email'] = $email; // Set stuff here

		header('Location: ./00_welcome.php');


	} else {
		//header('Location: /path/to/failed/login.php');
		echo "Login FAILURE!";

	}

} elseif ( $_POST["type"] == 2 ) { // Logout Request

	session_regenerate_id(true); // Create new ID to be safe
	$_SESSION = array(); // Unset all session variables
	$_SESSION['test_login'] = true; // Since this is the testing server, this is still true
	header('Location: ./index.php');

} else {
	// We got here on accident somehow
	header('HTTP/1.0 400 Bad Request');
}
