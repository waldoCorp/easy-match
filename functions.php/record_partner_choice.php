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

function record_partner_invitations($uuid,$partner_uuid,$confirm) {
    // Require table variables:
    require '/srv/nameServer/functions.php/table_variables.php';

    // connect to database
    require_once '/srv/nameServer/functions.php/db_connect.php';
    $db = db_connect();

    // MIGHT NEED TO DO THE REVERSE AS WELL FOR CONSISTENCY
    // MAYBE TRIGGER????

    if( $confirm ) {
      // Accepted invitation:
      $sql = "UPDATE $partners_table SET confirmed = true
              WHERE uuid = :partner_uuid AND partner_uuid = :uuid";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->execute();

    } else {
      // Rejected invitation:
      $sql = "DELETE FROM $partners_table
              WHERE uuid = :partner_uuid AND partner_uuid = :uuid";

      $stmt = $db->prepare($sql);
      $stmt->bindValue(':uuid',$uuid);
      $stmt->execute();

}
