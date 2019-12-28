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
 * add_new_user($email,$passwd,$uname);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $email : user's email address
 * @param string $passwd : clear-text password
 * @param string $uname : string of user's name / handle / whatever
 *
 * returns TRUE if insert was successful

**/

function add_new_user($email,$pass,$uname) {
	// Require table variables:
	require __DIR__ . '/table_variables.php';

	// Include database connection
	require __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	// make email lower case
        $email = strtolower($email);

	// hash input password
	$hashed_pass  = password_hash($pass, PASSWORD_DEFAULT);

	// Generate UUID:
	require_once __DIR__ . '/generate_uuidv4.php';
	$uuid = generate_uuid();

	//$current_date = mktime();
	$current_date = date('Y-m-d h:i:s');


	try {
		$sql = "INSERT INTO $users_table (uuid, email, create_date, last_login, password, username)
                        VALUES (:uuid, :email, :create_date, :last_login, :pass, :uname)
			ON CONFLICT DO NOTHING";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':create_date', $current_date);
		$stmt->bindValue(':last_login', $current_date);
		$stmt->bindValue(':pass', $hashed_pass);
		$stmt->bindValue(':uname', $uname);
		$success = $stmt->execute();
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}


	return $success;
}
