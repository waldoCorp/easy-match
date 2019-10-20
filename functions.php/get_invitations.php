<?php
/**
 * Function to get a list of current partner invitations
 *
 * Use of function:
 * require_once '../get_invitations.php';
 *
 *
 * $invitations = get_invitations($uuid);
 *
 * @param $uuid : UUID of the to find invitations for
 *
 * @return Array containing the uuid and email addresses of people who have
 * sent a partnership request.
 *
 * The format is array(uuid => email_address,...)
**/

function get_invitations($uuid) {
    // Require table variables:
    require '/srv/nameServer/functions.php/table_variables.php';

    // connect to database
    require_once '/srv/nameServer/functions.php/db_connect.php';
    $db = db_connect();

    // Find requests to this user:
    $sql = "SELECT uuid FROM $partners_table WHERE partner_uuid = :uuid
            AND confirmed = false";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $p_uuids = $stmt->fetchAll();
    // Convert to flat array:
    $p_uuids = call_user_func_array('array_merge',$p_uuids);
    array_shift($p_uuids);

    // Copy to new array to replace with emails:
    $emails = $p_uuids;

    array_walk($emails, 'get_emails_array');

    // Combine to make array($uuid => $email) pairs
    $output = array_combine($p_uuids,$emails);

    return $output;
}



// Helper function to allow for array_walk to work to get all emails:
function get_emails_array(&$uuid) {
    // Convert to emails:
    require_once '/srv/nameServer/functions.php/get_email.php';

    $uuid = get_email($uuid);
}
