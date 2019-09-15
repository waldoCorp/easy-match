<?php
/**
 * Function for creation of new users.
 * Takes a new contact email, table to insert into, and password.
 * Checks to ensure that email / username are not in use already.
 * Also checks to make sure that the entered passwords match.
 *
 * Example usage:
 * require_once '../add_new_user.php';
 *
 * add_new_user($table,$email,$passwd);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $table : Table to insert user in to
 * @param string $email : user's email address
 * @param string $passwd : hashed password
 *
 * returns TRUE if insert was successful

**/

function add_new_user($table,$email,$pass) {
	// Include database connection
	require_once '/srv/nameServer/functions.php/db_connect.php';

	// Connect to db
	$db = db_connect();

	// hash inputs
	$hashed_pass  = password_hash($pass, PASSWORD_DEFAULT);
	$hashed_email = password_hash($email, PASSWORD_DEFAULT);

	//$current_date = mktime();
	$current_date = date('Y-m-d h:i:s')


	try {
		$sql = "INSERT INTO $table (uuid, email, create_date, last_login, password) VALUES (:uuid, :email, :create_date, :last_login, :password)
			ON CONFLICT DO NOTHING";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $hashed_email);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':create_date', $current_date);
		$stmt->bindValue(':last_login', $current_date);
		$stmt->bindValue(':pass', $pass);
		$success = $stmt->execute();
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}


	return $success;
}
