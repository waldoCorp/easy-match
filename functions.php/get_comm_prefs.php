<?php
/**
 * Function to get the communcations preferences a user has selected:
 *
 * Example usage:
 * require_once '../get_comm_prefs.php';
 *
 *
 * $preferences = get_comm_prefs($uuid);
 *
 * @param $uuid : UUID of user whose preferences we are getting
 *
 * @return Nested array with the preferences the user has indicated
 *
**/

function get_comm_prefs($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    $sql = "SELECT all_comm, none, functional, promotion
            FROM $communication_preferences_table
	    WHERE uuid = :uuid;";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $result = $stmt->fetchAll();

    $output = array(
                'all_comm'=>true,
                'none'=>false,
                'functional'=>false,
                'promotion'=>false
              );

    // If we have an entry, prepare our return:
    if( !empty($result) ) {
      $output = $result[0];
    }

    return $output;
}

