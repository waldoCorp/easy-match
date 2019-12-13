<?php
/**
 * Function to update a user's username
 *
 * Example usage:
 * require_once '../update_username.php';
 *
 * update_username($uuid,$uname);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid: user's UUID
 * @param string $uname : new username
 *
 * returns a boolean TRUE for a successful update
**/

function update_username($uuid,$uname) {
	// Require table variables:
	require __DIR__ . '/table_variables.php';

	// Include database connection
	require_once __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	try {
		$sql = "UPDATE $users_table SET username = :uname
			WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$stmt->bindValue(':uname', $uname);
		$success = $stmt->execute();
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}


	return $success;
}