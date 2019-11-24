<?php
/**
 * Function to get the name preferences a user has selected:
 *
 * Example usage:
 * require_once '../get_preferences.php';
 *
 *
 * $preferences = get_preferences($uuid);
 *
 * @param $uuid : UUID of user whose preferences we are getting
 *
 * @return Nested array with the preferences the user has indicated
 *
**/

function get_preferences($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    $sql = "SELECT gender, first_letter, last_letter, popularity
            FROM $preferences_table
	    WHERE uuid = :uuid;";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $result = $stmt->fetchAll();

    $output = null;

    // If we have an entry, prepare our return:
    if( !empty($result) ) {
      // Turn gender into nested array from string:

      $output = $result[0];
    }

    return $output;
}

