<?php
/**
 * Function to get UUID of user on login:
 *
 * Example usage:
 * require_once '../get_uuid.php';
 *
 * $uuid = get_uuid($email);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $email : user's email address
 *
 * returns User's UUID (string)
 *
**/

function get_uuid($email) {
        // Require table variables:
        require '/srv/nameServer/functions.php/table_variables.php';

	// Include database connection
	require_once '/srv/nameServer/functions.php/db_connect.php';

	$email = strtolower($email);

	// Connect to db
	$db = db_connect();

	// Set default hash:
	$hash = "";
	// Get password hash for this user:
	try {
		$sql = "SELECT uuid FROM $users_table WHERE email = :email";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email);
		$success = $stmt->execute();

		while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$uuid = $row["uuid"];
		}
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

	return $uuid;
}
