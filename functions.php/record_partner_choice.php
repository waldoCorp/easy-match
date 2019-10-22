<?php
/**
 * Function to respond to a partner invitation
 *
 * It either deletes the row in the partners table (if no)
 * or keeps it and changes confirmed to true
 *
 * Use of function:
 * require_once '../record_partner_choice.php';
 *
 *
 * record_partner_choice($uuid,$partner_uuid,$confirm);
 *
 * @param $uuid : UUID of the person responding to the invitation
 * @param $partner_uuid : UUID of the person who sent the invitation
 * @param $confirm : Boolean to determine if the invitation was accepted (true)
 *
 * @return No Return
 *
**/

function record_partner_choice($uuid,$partner_uuid,$confirm) {
    // Require table variables:
    require '/srv/nameServer/functions.php/table_variables.php';

    // connect to database
    require_once '/srv/nameServer/functions.php/db_connect.php';
    $db = db_connect();

    if( $confirm ) {
      // Accepted invitation:
      // Create initial record:
      $sql = "INSERT INTO $partners_table
             (uuid, partner_uuid, proposer, pair_confirm_date, confirmed, pair_propose_date)
             VALUES (:uuid, :partner_uuid, false, current_timestamp, true, current_timestamp)
             ON CONFLICT DO NOTHING;";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();

      // Update first record with pair-date and confirmation:
      $sql = "UPDATE $partners_table SET (pair_confirm_date, confirmed) =
              (current_timestamp, true) WHERE uuid = :partner_uuid
              AND partner_uuid = :uuid;";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();

      // Update second record to have original proposal time:
      $sql = "UPDATE $partners_table SET pair_propose_date =
                (SELECT pair_propose_date from $partners_table
                 WHERE uuid = :partner_uuid AND partner_uuid = :uuid)
              WHERE uuid = :uuid AND partner_uuid = :partner_uuid;";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();

    } else {

      // Rejected invitation:
      $sql = "DELETE FROM $partners_table
              WHERE uuid = :partner_uuid AND partner_uuid = :uuid";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();
    }
}
