<?php
/**
 * Function to get a user's username (from UUID)
 *
 * Example usage:
 * require_once '../get_username.php';
 *
 * $uname = get_username($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid : user's UUID
 *
 * returns User's Username (string)
 *
**/

function get_username($uuid) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

	// Include database connection
	require_once __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	// Get password hash for this user:
	try {
		$sql = "SELECT username FROM $users_table WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$success = $stmt->execute();

		while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$uname = $row["username"];
		}
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

	return $uname;
}

