<?php
/**
 * Function to create a selector and token for users to unsubscribe from emails.
 * These are put into the database as well as returned as an html address for emailing.
 *
 * This will update an existing entry if one already exists.
 *
 * This also triggers deletion of old tokens (>1 week old)
 *
 * usage:
 * require_once '../create_unsubscribe_token.php';
 *
 *
 * <code>
 * $url = create_password_token($uuid);
 * <code>
 *
 *
 *
 * @param $uuid : the uuid of the user unsubscribing from our communications
 *
 * return string : url to send in an email to unsubscribe
 *
 * @author Ben Cerjan
 *
*/

function create_unsubscribe_token($uuid) {

	$token = bin2hex(openssl_random_pseudo_bytes(16));

	$urlToEmail = 'https://easymatch.waldocorp.com/unsubscribe.php?'.
			http_build_query([
				'token' => $token
			]);

	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('P1W')); // 1 Week duration


        // Require table variables:
        require __DIR__ . '/table_variables.php';

	require_once __DIR__ . '/db_connect.php';
	$db = db_connect();

	$sql = "INSERT INTO $unsubscribe_token_table (uuid, token, expires)
		VALUES (:uuid, :token, :expires);";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->bindValue(':token', $token);
	$stmt->bindValue(':expires',$expires->format('Y-m-d\TH:i:s'));
	$stmt->execute();

	$sql = "DELETE FROM $unsubscribe_token_table WHERE expires < now() - interval '1 week'";
	$stmt = $db->prepare($sql);
	$stmt->execute();


	return $urlToEmail;
}
