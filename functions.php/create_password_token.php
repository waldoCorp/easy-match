<?php
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
 * $url = create_password_token($email);
 * <code>
 *
 *
 *
 * @param $email : the email address being reset
 *
 * return string : url to send in an email for password reset
 *
 * @author Ben Cerjan
 *
*/

function create_password_token($email) {
	$selector = bin2hex(openssl_random_pseudo_bytes(8));
	$token = bin2hex(openssl_random_pseudo_bytes(32));

	// email to lower case
	$email = strtolower($email);

	// NEEDS TO BE FIXED:
	$urlToEmail = 'https://test.easydivider.com/logged_in/00_login_password_setup.php?'.
			http_build_query([
				'selector' => $selector,
				'validator' => $token
			]);

	$expires = new DateTime('NOW');
	$expires->add(new DateInterval('P1D')); // 1 Day duration


        // Require table variables:
        require '/srv/nameServer/functions.php/table_variables.php';

	require_once '/srv/nameServer/functions.php/db_connect.php';
	$db = db_connect();

	$sql = "INSERT INTO $password_recovery_table (email, selector, token, expires) VALUES (:email, :selector, :token, :expires)
		ON CONFLICT (email) DO UPDATE SET
			selector=EXCLUDED.selector,
			token=EXCLUDED.token,
			expires=EXCLUDED.expires;";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':email',$email);
	$stmt->bindValue(':selector',$selector);
	$stmt->bindValue(':token', hash('sha256', $token));
	$stmt->bindValue(':expires',$expires->format('Y-m-d\TH:i:s'));
	$stmt->execute();

	$sql = "DELETE FROM $recovery_table WHERE expires < now() - interval '1 days'";
	$stmt = $db->prepare($sql);
	$stmt->execute();


	return $urlToEmail;
}
