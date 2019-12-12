<?php
/**
 * Function to delete a user's account
 * (should CASCADE to all other content)
 *
 * Example usage:
 * require_once '../delete_account.php';
 *
 * delete_account($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid : user's UUID (about to be past-tense)
 *
 * no return
 *
**/

function delete_account($uuid) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

	// Include database connection
	require_once __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	// Set default hash:
	$hash = "";
	// Get password hash for this user:
	try {
		$sql = "DELETE FROM $users_table WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$success = $stmt->execute();

	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

}

