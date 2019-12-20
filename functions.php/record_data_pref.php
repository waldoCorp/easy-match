<?php
/**
 * Function to set a user's data sharing preferences
 *
 * @param $uuid : UUID of user making the request
 * @param $preference : Boolean. True means opted-out of data sharing.
 *
 *
 * @return No return, silent execution
**/

function record_data_pref($uuid, $preference) {

    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    $sql = "UPDATE $users_table SET data_opt_out = :data_pref
            WHERE uuid = :uuid;";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->bindValue(':data_pref',$preference, PDO::PARAM_BOOL);
    $stmt->execute();
}

