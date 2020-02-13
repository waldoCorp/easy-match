<?php
/**
 * Function to add emails to the unsubscribed list
 * Takes an email as input and adds it to the
 * unsubscribed table
 *
 * Example usage:
 * require_once '../add_to_unsubscribe';
 * add_to_unsubscribe($email);
 *
 * @author Lief Esbenshade
 * @param string $email : email address
 *
 * returns TRUE if insert was succesful
**/


function add_to_unsubscribe($email) {
	require __DIR__ . '/table_variables.php';
	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$email = strtolower($email);

	try {
 		$sql = "INSERT INTO $unsub_table (email)
			VALUES (:email)
			ON CONFLICT DO NOTHING";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email);
		$success = $stmt->execute();
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

	return $success;
}
