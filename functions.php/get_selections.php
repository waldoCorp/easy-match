<?php
/**
 * Function to get the names a user has ranked (both positive and negative)
 *
 * Example usage:
 * require_once '../get_selections.php';
 *
 *
 * $array = get_selections($uuid);
 *
 * @param $uuid : UUID of user whose selections we are considering
 * @return Nested array with "name" and "selected" values for each name
 * the user has ranked thus far.
**/

function get_selections($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    $sql = "SELECT name, selected FROM $selections_table
	    WHERE uuid = :uuid
	    ORDER BY name;";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $result = $stmt->fetchAll();

    return $result;
}

