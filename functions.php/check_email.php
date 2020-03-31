<?php
/**
 *    Copyright (c) 2020 Ben Cerjan, Lef Esbenshade
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

/* Function to check if an email already exists in the database
 *
 *
 * usage:
 * require_once '../check_email.php';
 *
 *
 * <code>
 * $ans = check_email($email);
 * <code>
 *
 *
 * Returns a boolean (T for already exists)
 * @param $email : the email address to check
 *
 * return boolean
 *
 * @author Ben Cerjan
 *
*/

function check_email($email) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$email = strtolower($email);

	$sql = "SELECT email FROM $users_table WHERE email = :email";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':email',$email);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$result = $row["email"];
	}

	return isset($result);
}
