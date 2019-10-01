<?php
/**
 * Function to get a list of names for a user to upvote/downvote
 * Takes user's email address and table names as arguments
 *
 * Example usage:
 * <code>
 * require_once './functions.php/get_names.php'
 * <code>
 *
 * Use of function:
 * $names = get_names($uuid,$n);
 *
 * On success returns a flat array of names
 *
 * @param string $uuid : UUID of user making the request
 * @param integer $n : Number of names to return
 * @return array : Returns an array of names
 *
**/

function get_names($uuid,$n) {
        // Connect to database
        require_once '/srv/nameServer/functions.php/db_connect.php';
        $db = db_connect();

	$sql = "
	SELECT COALESCE(ps.name, rs.name, ss.name) as name

	  FROM (
		-- Get partner selections
    		SELECT name, true AS priority
    		FROM $selection_table s
    		LEFT JOIN $partners_table p ON s.uuid = p.partner_uuid
    		WHERE p.uuid = :uuid AND s.selected = true
	  ) AS ps

	  FULL JOIN (
	  -- get all the names from the db
    		SELECT name
    		FROM $names_table n
	  ) AS rs ON ps.name = rs.name

	  LEFT JOIN (
	  -- remove anything already seen
		SELECT name
		FROM $selection_table
    		WHERE uuid = :uuid
	  ) AS ss ON ps.name = ss.name

	WHERE ss.name IS NULL
	-- randomizes the returned list, but keeps partner names on top --
	ORDER BY priority, random()
	LIMIT :n;";

	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->bindValue(':n',$n,PDO::PARAM_INT);
	$stmt->execute();

        // Get names from specified table
	$names = $stmt->fetchAll();

	// Convert to 1D array
	$names = call_user_func_array('array_merge',$names);
	array_shift($names); // First element is duplicated

return $names;

}
