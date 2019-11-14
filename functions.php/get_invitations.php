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
    require __DIR__ . '/table_variables.php';

    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // Find requests to this user:
    $sql = "WITH excluded_uuids AS (
              -- Find UUID's we've responded to already
              SELECT partner_uuid FROM $partners_table WHERE
              uuid = :uuid AND confirmed = false
            ), p_uuids AS (
              -- Find potential candidates that could be an invitation
              SELECT uuid FROM $partners_table WHERE
              partner_uuid = :uuid AND confirmed = false
            )

            -- Select those UUIDs that we haven't responded to yet
            SELECT uuid FROM p_uuids p
            WHERE NOT EXISTS (
              SELECT FROM excluded_uuids
              WHERE uuid = p.uuid
            );";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $p_uuids = $stmt->fetchAll();

    if( !empty($p_uuids) ) {
      // Convert to flat array:
      $p_uuids = call_user_func_array('array_merge',$p_uuids);
      array_shift($p_uuids);

      // Copy to new array to replace with emails:
      $emails = $p_uuids;

      require_once __DIR__ . '/get_email.php';

      array_walk($emails, 'get_emails_array');

      // Combine to make array($uuid => $email) pairs
      $output = array_combine($p_uuids,$emails);
    } else {
      $output = $p_uuids;
    }

    return $output;
}
