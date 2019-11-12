<?php
/**
 * Function to update a user's password
 *
 * Example usage:
 * require_once '../update_password.php';
 *
 * update_password($email,$passwd);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid: user's UUID
 * @param string $passwd : clear-text password
 *
 * returns a boolean TRUE for a successful update
**/

function update_password($uuid,$pass) {
	// Require table variables:
	require __DIR__ . '/table_variables.php';

	// Include database connection
	require_once __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	// hash inputs
	$hashed_pass  = password_hash($pass, PASSWORD_DEFAULT);

	try {
		$sql = "UPDATE $users_table SET password = :pass
			WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$stmt->bindValue(':pass', $hashed_pass);
		$success = $stmt->execute();
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}


	return $success;
}
