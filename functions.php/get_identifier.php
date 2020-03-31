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
 * Function to get a user's username or email
 * Preferentially returns username, but if that is unset
 * it will return their email instead.
 *
 * Example usage:
 * require_once '../get_identifier.php';
 *
 * $uname = get_identifier($uuid);
 *
 *
 *
 * @author Ben Cerjan
 * @param string $uuid : user's UUID
 *
 * returns User's Identifier (username or email) (string)
 *
**/

function get_identifier($uuid) {
	// Require functions to get username/email:
        require_once __DIR__ . '/get_username.php';
        require_once __DIR__ . '/get_email.php';

	$uname = get_username($uuid);
	$email = get_email($uuid);

	if( empty($uname) ) {
		$uname = $email;
	}

	return $uname;
}


// Helper function to allow for array_walk to work to get all usernames:
function get_identifier_array(&$uuid) {
    // Convert to usernames:
    $uuid = get_identifier($uuid);
}
