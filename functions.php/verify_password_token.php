<?php
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
	require '/srv/nameServer/functions.php/table_variables.php';
	require_once '/srv/nameServer/functions.php/db_connect.php';
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
