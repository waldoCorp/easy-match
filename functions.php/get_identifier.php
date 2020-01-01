<?php
/**
 * Function to get a user's username or email
 * Preferentially returns username, but if that is unset
 * it will return their email instead.
 *
 * Example usage:
 * require_once '../get_identifier.php';
 *
 * $uname = get_identifier($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid : user's UUID
 *
 * returns User's Identifier (username or email) (string)
 *
**/

function get_identifier($uuid) {
	// Require functions to get username/email:
        require_once __DIR__ . '/get_username.php';
        require_once __DIR__ . '/get_email.php';

	$uname = get_username($uuid);
	$email = get_email($uuid);

	if( empty($uname) ) {
		$uname = $email;
	}

	return $uname;
}


// Helper function to allow for array_walk to work to get all usernames:
function get_identifier_array(&$uuid) {
    // Convert to usernames:
    $uuid = get_identifier($uuid);
}

