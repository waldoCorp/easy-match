<?php
/**
 * Function to track when a user last logged in:
 *
 * Example usage:
 * require_once '../update_last_login.php';
 *
 * update_last_login($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid: user's UUID
 *
 * no return
**/

function update_last_login($uuid) {
	// Require table variables:
	require __DIR__ . '/table_variables.php';

	// Include database connection
	require_once __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	try {
		$sql = "UPDATE $users_table SET last_login = current_timestamp
			WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$stmt->execute();
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

}
