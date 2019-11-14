<?php
/**
 * Function to get the list of names that match between two users
 * Checks new_matches table, and prepends * to those names, then 
 * removes records from new_matches.
 *
 * Example usage:
 * <code>
 * require_once './functions.php/get_matching_names_list.php'
 * <code>
 *
 * Use of function:
 * $names = get_matching_names_list($uuid,$partner_uuid);
 *
 * On success returns a flat array of names
 *
 * @param string $uuid : UUID of user making the request
 * @param integer $partner_uuid : UUID of the current partner to match with
 * @return array : Returns an array of names
 *
**/

function get_matching_names_list($uuid,$partner_uuid) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

        // Connect to database
        require_once __DIR__ . '/db_connect.php';
        $db = db_connect();

	$sql = "
	SELECT CONCAT(star, matchs.name) AS name FROM
		(SELECT name FROM $selections_table
        	WHERE uuid = :uuid AND selected = true

		INTERSECT

		SELECT name FROM $selections_table
       		WHERE uuid = :partner_uuid AND selected = true
		) AS matchs
	LEFT JOIN
		(SELECT name, '*' AS star FROM $new_matches_table
	 	WHERE uuid = :uuid AND
		partner_uuid = :partner_uuid
		) AS new on new.name = matchs.name;
	";

	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->bindValue(':partner_uuid',$partner_uuid);
	$stmt->execute();

        // Get names from specified table
	$names = $stmt->fetchAll();

	// Remove records from new matchs as they have now been displayed
	$sql2 = "
	DELETE FROM $new_matches_table
	WHERE uuid = :uuid AND partner_uuid = :partner_uuid;
	";

	$stmt2 = $db->prepare($sql2);
//	$stmt2->execute();

	// If there is any overlap, prepare for output:
	if( !empty($names) ) {
	  // Convert to 1D array
	  $names = call_user_func_array('array_merge',$names);
	  array_shift($names); // First element is duplicated
	} else {
	  $names = null;
	}

return $names;

}
