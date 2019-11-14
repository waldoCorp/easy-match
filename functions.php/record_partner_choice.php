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
    require __DIR__ . '/table_variables.php';

    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    if( $confirm ) {
      // Accepted invitation:
      // Create initial record, updating to confirm if someone changes mind:
      $sql = "INSERT INTO $partners_table
             (uuid, partner_uuid, proposer, pair_confirm_date, confirmed, pair_propose_date)
             SELECT CAST(:uuid AS VARCHAR), CAST(:partner_uuid AS VARCHAR), false, current_timestamp, true,
               pair_propose_date FROM $partners_table WHERE uuid = :partner_uuid and partner_uuid = :uuid
             ON CONFLICT ON CONSTRAINT partners_uuid_partner_uuid_proposer_key
             DO UPDATE SET
             (confirmed, pair_confirm_date) =
             (EXCLUDED.confirmed, EXCLUDED.pair_confirm_date);";


      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid, PDO::PARAM_STR);
      $stmt->bindValue(':partner_uuid',$partner_uuid, PDO::PARAM_STR);
      $stmt->execute();

      // Update original record with pair-date and confirmation:
      $sql = "UPDATE $partners_table SET (pair_confirm_date, confirmed) =
              (current_timestamp, true) WHERE uuid = :partner_uuid
              AND partner_uuid = :uuid;";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();

    } else {

      // Rejected invitation:

      // Leave records in case they change their mind:
      $sql = "INSERT INTO $partners_table
             (uuid, partner_uuid, proposer, pair_confirm_date, confirmed, pair_propose_date)
             SELECT CAST(:uuid AS VARCHAR), CAST(:partner_uuid AS VARCHAR), false, current_timestamp, false,
               pair_propose_date FROM $partners_table WHERE uuid = :partner_uuid and partner_uuid = :uuid
             ON CONFLICT ON CONSTRAINT partners_uuid_partner_uuid_proposer_key
             DO UPDATE SET
             (confirmed, pair_confirm_date) =
             (EXCLUDED.confirmed, EXCLUDED.pair_confirm_date);";


      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid, PDO::PARAM_STR);
      $stmt->bindValue(':partner_uuid',$partner_uuid, PDO::PARAM_STR);
      $stmt->execute();

      // In case we're de-friending someone:
      $sql = "UPDATE $partners_table SET (pair_confirm_date, confirmed) =
              (NULL, false) WHERE uuid = :partner_uuid
              AND partner_uuid = :uuid;";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->bindValue(':partner_uuid',$partner_uuid);
      $stmt->execute();


    }
}
