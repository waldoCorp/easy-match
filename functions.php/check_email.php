<?php
/* Function to check if an email already exists in the database
 *
 *
 * usage:
 * require_once '../check_email.php';
 *
 *
 * <code>
 * $ans = check_email($table,$email);
 * <code>
 *
 *
 * Returns a boolean (T for already exists)
 * @param string $table : the database table to search in
 * @param $email : the email address to check
 *
 * return boolean
 *
 * @author Ben Cerjan
 *
*/

function check_email($table,$email) {
	require_once '/srv/nameServer/functions.php/db_connect.php';
	$db = db_connect();

	$sql = "SELECT email FROM $table WHERE email = :email";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':email',$email);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$result = $row["email"];
	}

	return isset($result);
}
