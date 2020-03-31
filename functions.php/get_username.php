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
 * Function to get a user's username (from UUID)
 *
 * Example usage:
 * require_once '../get_username.php';
 *
 * $uname = get_username($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid : user's UUID
 *
 * returns User's Username (string)
 *
**/

function get_username($uuid) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

	// Include database connection
	require_once __DIR__ . '/db_connect.php';

	// Connect to db
	$db = db_connect();

	// Get password hash for this user:
	try {
		$sql = "SELECT username FROM $users_table WHERE uuid = :uuid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uuid', $uuid);
		$success = $stmt->execute();

		while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$uname = $row["username"];
		}
	} catch(PDOException $e) {
		die('ERROR: ' . $e->getMessage() . "\n");
	}

	return $uname;
}


// Helper function to allow for array_walk to work to get all usernames:
function get_usernames_array(&$uuid) {
    // Convert to emails:
    $uuid = get_username($uuid);
}
