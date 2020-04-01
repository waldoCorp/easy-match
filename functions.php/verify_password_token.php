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
 * Function to verify a selector / validator combo for password creation / recovery.
 *
 *
 * usage:
 * require_once '../verify_password_token.php';
 *
 *
 * <code>
 * $url = verify_password_token($selector,$validator);
 * <code>
 *
 *
 *
 * @param $selector : selector string used to identify reset
 * @param $validator : validator string used to certify reset
 *
 * return string : UUID that is about to be reset (or empty if there was an error)
 *
 * @author Ben Cerjan
 *
*/

function verify_password_token($selector,$validator) {
	// Table variables and connection to DB:
	require __DIR__ . '/table_variables.php';
	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$sql = "SELECT * FROM $password_recovery_table WHERE selector = :selector AND
		expires >= NOW();";

	$stmt = $db->prepare($sql);
	$stmt->bindValue(':selector',$selector);
	$stmt->execute();

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $returnUUID = null;
	if (!empty($results)) {
		$calc = hash('sha256',$validator);
		if (hash_equals($calc,$results[0]['token'])) {
			// Valid Reset
			$returnUUID = $results[0]['uuid'];
		}

		$sql = "DELETE FROM $password_recovery_table WHERE selector = :selector;";

		$stmt = $db->prepare($sql);
		$stmt->bindValue(':selector',$selector);
		$stmt->execute();

	}


	return $returnUUID;
}

?>
