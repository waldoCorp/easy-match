<?php
/**
 * Function to get the number of new matches that a user has (and with whom)
 * Checks new_matches table for this user's new matches.
 *
 *
 * Example usage:
 * <code>
 * require_once './functions.php/get_new_matches_number.php'
 * <code>
 *
 * Use of function:
 * $numbers = get_new_matches_number($uuid);
 *
 * On success returns a nested array: array( username/email=># )
 * so the partner's username or email is a key to the number of new matches
 * we have with that partner.
 *
 * @param string $uuid : UUID of user making the request
 *
 * @return array : Returns an array with username/email => # of matches
 *
**/

function get_new_matches_number($uuid) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

        // Connect to database
        require_once __DIR__ . '/db_connect.php';
        $db = db_connect();

	$sql = "SELECT partner_uuid,COUNT(*)
		FROM $new_matches_table
		WHERE uuid = :uuid
		GROUP BY partner_uuid;";


	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->execute();

        // Get Partners (and number of matches)
	$partners = $stmt->fetchAll();

	$output = array();

	// If there are any new matches, format the result:
	if( !empty($partners) ) {
          require_once __DIR__ . '/get_identifier.php';
	  // Convert to username/email => number array:
	  foreach( $partners as $partner ) {
	    $ident = get_identifier($partner['partner_uuid']);
	    $output[$ident] = $partner['count'];
	  }
	} else {
	  $output = null;
	}

return $output;

}
