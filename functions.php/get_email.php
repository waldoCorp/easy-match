<?php
/**
 * Function to get email from UUID:
 *
 * Example usage:
 * require_once '../get_email.php';
 *
 * $email = get_email($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid : user's UUID
 *
 * returns User's UUID (string)
 *
**/

function get_email($uuid) {
        // Require table variables:
        require '/srv/nameServer/functions.php/table_variables.php';

	// Include database connection
	require_once '/srv/nameServer/functions.php/db_connect.php';

	// Connect to db
	$db = db_connect();

	// Set default hash:
	$hash = "";
	// Get password hash for this user:
	try {
		$sql = "SELECT email FROM $users_table WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$success = $stmt->execute();

		while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$email = $row["email"];
		}
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

	return $email;
}
