<?php
/**
 * Function to validate login of users
 *
 * Example usage:
 * require_once '../password_check.php';
 *
 * $response = password_check($table,$email,$password);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $table : Table to insert user in to
 * @param string $email : user's email address
 * @param string $passwd : clear-text password (this function hashes it)
 *
 * returns TRUE if password is correct
 *
**/
//session_start();

function password_check($table,$email,$pass) {
	// Include database connection
	require_once '/srv/nameServer/functions.php/db_connect.php';

	// Connect to db
	$db = db_connect();

	// Set default hash:
	$hash = "";
	// Get password hash for this user:
	try {
		$sql = "SELECT password FROM $table WHERE email = :email";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email);
		$success = $stmt->execute();

		while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$hash = $row["password"];
		}
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

	// Validate Password
	if ($success) {
		$success = password_verify($pass, $hash);
	}

	return $success;
}
