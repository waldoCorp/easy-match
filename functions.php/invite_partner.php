<?php
/**
 * Function to send a partner request to another (potentially new) user
 *
 * Use of function:
 * require_once '../invite_partner.php';
 *
 *
 * invite_partner($email,$orig_uuid);
 *
 * @param $email : Email for the user we are adding
 * @param $orig_uuid : UUID of the person sending the invitation
 *
 * @return No return, silent execution
**/

function invite_partner($email, $orig_uuid) {
    // Require table variables:
    require '/srv/nameServer/functions.php/table_variables.php';

    // connect to database
    require_once '/srv/nameServer/functions.php/db_connect.php';
    $db = db_connect();


    // First, we need to add the new user to the database
    require_once '/srv/nameServer/functions.php/add_new_user.php';

    // Giving them a fake password (if they do not already have one)
    $pass = bin2hex(random_bytes(5));
    add_new_user($email, $pass); // This will silently fail if the user already exists

    // Now, get the UUID of the new user:
    require_once '/srv/nameServer/functions.php/get_uuid.php';
    $partner_uuid = get_uuid($email);

    // Stop weirdness -- if $orig_uuid = $partner_uuid someone put in their own email
    if( $orig_uuid == $partner_uuid ) {
      exit();
    }

    // Send an email here???

    // First check if we're "responding" by inviting someone who invited us:
    $sql = "SELECT * from $partners_table WHERE uuid = :partner_uuid AND
            partner_uuid = :uuid";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$orig_uuid);
    $stmt->bindValue(':partner_uuid',$partner_uuid);
    $stmt->execute();

    $rows = $stmt->fetchAll();
    $new_invite = empty($rows); // If not empty, this is not a new invite

    if( $new_invite ) {
      // This is a fresh pairing:
      // Now, we can insert this pairing into the partners table:
      // Only insert the "proposer's" version of the record:
      $sql = "INSERT INTO $partners_table
              (uuid, partner_uuid, proposer, pair_propose_date)
              VALUES (:uuid, :partner_uuid, true, current_timestamp)
              ON CONFLICT DO NOTHING;";


      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$orig_uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();
    } else {
      // This person is unknowningly 'responding' to an invitation:
      require_once '/srv/nameServer/functions.php/record_partner_choice.php';
      record_partner_choice($orig_uuid, $partner_uuid, true);
    }
}

