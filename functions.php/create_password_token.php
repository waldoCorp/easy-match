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
 * Function to create a selector and token for password creation / recovery.
 * These are put into the database as well as returned as an html address for emailing.
 *
 * This will update an existing entry if one already exists.
 *
 * This also triggers deletion of old passwords (>24 hours old)
 *
 * usage:
 * require_once '../create_password_token.php';
 *
 *
 * <code>
 * $url = create_password_token($uuid);
 * <code>
 *
 *
 *
 * @param $uuid : the uuid of the user getting a new password
 *
 * return string : url to send in an email for password reset
 *
 * @author Ben Cerjan
 *
*/

function create_password_token($uuid) {
	$selector = bin2hex(openssl_random_pseudo_bytes(8));
	$token = bin2hex(openssl_random_pseudo_bytes(32));

	// NEEDS TO BE FIXED:
	$urlToEmail = 'https://easymatch.waldocorp.com/password_set.php?'.
			http_build_query([
				'selector' => $selector,
				'validator' => $token
			]);

	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('P1D')); // 1 Day duration


        // Require table variables:
        require __DIR__ . '/table_variables.php';

	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$sql = "INSERT INTO $password_recovery_table (uuid, selector, token, expires) VALUES (:uuid, :selector, :token, :expires)
		ON CONFLICT (uuid) DO UPDATE SET
			selector=EXCLUDED.selector,
			token=EXCLUDED.token,
			expires=EXCLUDED.expires;";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->bindValue(':selector',$selector);
	$stmt->bindValue(':token', hash('sha256', $token));
	$stmt->bindValue(':expires',$expires->format('Y-m-d\TH:i:s'));
	$stmt->execute();

	$sql = "DELETE FROM $password_recovery_table WHERE expires < now() - interval '1 days'";
	$stmt = $db->prepare($sql);
	$stmt->execute();


	return $urlToEmail;
}
