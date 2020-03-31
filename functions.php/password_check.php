<?php
/**
 *    Copyright (c) 2020 Ben Cerjan, Lief Esbenshade
 *
 *    This file is part of Easy Match.
 *
 *    Easy Match is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    Easy Match is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.
**/

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
 * @param string $email : user's email address
 * @param string $passwd : clear-text password (this function hashes it)
 *
 * returns TRUE if password is correct
 *
**/

function password_check($email,$pass) {
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
		$sql = "SELECT password FROM $users_table WHERE email = :email";
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
