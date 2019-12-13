<?php
/**
 * Function to get a list of current rejected partners
 *
 * Use of function:
 * require_once '../get_rejected_partners.php';
 *
 *
 * $rej_partners = get_rejected_partners($uuid);
 *
 * @param $uuid : UUID of the to find partners for
 *
 * @return Array containing the uuid and email addresses of people who have
 * sent a partnership request.
 *
 * The format is array(uuid => array(email=>email_address,uname=>username),...)
**/

function get_rejected_partners($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';

    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // Find partners for this user:
    $sql = "SELECT partner_uuid FROM $partners_table WHERE uuid = :uuid
            AND confirmed = false AND proposer = false";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $p_uuids = $stmt->fetchAll();
    // Convert to flat array:
    $p_uuids = call_user_func_array('array_merge',$p_uuids);
    array_shift($p_uuids);

    // Copy to new array to replace with emails:
    require_once __DIR__ . '/get_email.php';
    require_once __DIR__ . '/get_username.php';

    $output = array();
    foreach( $p_uuids as $p_uuid ) {
      $output[$p_uuid] = array(
                            'email' => get_email($p_uuid),
                            'uname' => get_username($p_uuid)
                           );
    }

    return $output;
}
