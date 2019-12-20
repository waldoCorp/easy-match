<?php
/**
 * Function to get the data opt-out preferences a user has selected:
 *
 * Example usage:
 * require_once '../get_data_pref.php';
 *
 *
 * $pref = get_data_pref($uuid);
 *
 * @param $uuid : UUID of user whose preferences we are getting
 *
 * @return Boolean true/false. True indicates that the user has opted out.
 *
**/

function get_data_pref($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    $sql = "SELECT data_opt_out
            FROM $users_table
	    WHERE uuid = :uuid;";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $result = $stmt->fetchAll();

    return $result[0]['data_opt_out'];
}

