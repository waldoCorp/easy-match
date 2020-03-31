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
 * Function to create a token for data downloads
 * These are put into the database so that the shiny server can check
 * against them.
 *
 * This will update an existing entry if one already exists.
 *
 * This also triggers deletion of old tokens (>24 hours old)
 *
 * usage:
 * require_once '../create_data_token.php';
 *
 *
 * <code>
 * $token = create_data_token($uuid);
 * <code>
 *
 *
 *
 * @param $uuid : the uuid of the user getting a new password
 *
 * return string : token to pass to the shiny server
 *
 * @author Ben Cerjan
 *
*/

function create_data_token($uuid) {
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('PT5M')); // 5 Min duration


        // Require table variables:
        require __DIR__ . '/table_variables.php';

	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$sql = "INSERT INTO $data_token_table (uuid, token, expires) VALUES (:uuid, :token, :expires)
		ON CONFLICT (uuid) DO UPDATE SET
			token=EXCLUDED.token,
			expires=EXCLUDED.expires;";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->bindValue(':token',$token);
	$stmt->bindValue(':expires',$expires->format('Y-m-d\TH:i:s'));
	$stmt->execute();

	$sql = "DELETE FROM $password_recovery_table WHERE expires < now() - interval '1 days'";
	$stmt = $db->prepare($sql);
	$stmt->execute();


	return $token;
}
