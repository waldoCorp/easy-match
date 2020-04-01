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
 * Function to verify a token for unsubscribing from emails
 *
 *
 * usage:
 * require_once '../verify_unsubscribe_token.php';
 *
 *
 * <code>
 * $bool = verify_unsubscribe_token($token);
 * <code>
 *
 *
 *
 * @param $token : The token we are checking for in the table
 *
 * return string : Email of the user that is getting unsubscribed (or an empty string if none)
 *
 * @author Ben Cerjan
 *
*/

function verify_unsubscribe_token($token) {
	// Table variables and connection to DB:
	require __DIR__ . '/table_variables.php';
	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$sql = "SELECT * FROM $unsubscribe_token_table WHERE token = :token AND
		expires >= NOW();";

	$stmt = $db->prepare($sql);
	$stmt->bindValue(':token',$token);
	$stmt->execute();

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $returnUUID = null;
	if (!empty($results)) {
		// Valid Reset
		$returnUUID = $results[0]['uuid'];

		$sql = "DELETE FROM $unsubscribe_token_table WHERE token = :token;";

		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token',$token);
		$stmt->execute();

	}

	return $returnUUID;
}
