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
    require __DIR__ . '/table_variables.php';

    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // email to lower case
    $email = strtolower($email);

    // First, we need to add the new user to the database
    require_once __DIR__ . '/add_new_user.php';

    // Giving them a fake password (if they do not already have one)
    $pass = bin2hex(random_bytes(15));
    $uname = null; // empty username to start with
    add_new_user($email, $pass, $uname); // This will silently fail if the user already exists

    // Now, get the UUID of the new user:
    require_once __DIR__ . '/get_uuid.php';
    $partner_uuid = get_uuid($email);

    // Stop weirdness -- if $orig_uuid = $partner_uuid someone put in their own email
    if( $orig_uuid == $partner_uuid ) {
      exit();
    }


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

      // Only continue to email-sending stuff if the recipient
      // has not opted out:
      require_once __DIR__ . '/get_comm_prefs.php';
      $partner_prefs = get_comm_prefs($partner_uuid);

      if( $partner_prefs['all_comm'] || $partner_prefs['functional'] ) {

        // To prevent spam, check if this invite has already been sent:
        $sql = "SELECT pair_propose_date FROM $partners_table WHERE
                uuid = :uuid AND partner_uuid = :partner_uuid";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':uuid',$orig_uuid);
        $stmt->bindValue(':partner_uuid',$partner_uuid);
        $stmt->execute();
        $ans = $stmt->fetchAll();

        $date = new DateTime($ans[0]["pair_propose_date"]);
        $now = new DateTime("now", new DateTimeZone("America/Chicago"));
        $diff = abs($date->getTimestamp() - $now->getTimestamp());

        // Send an email here if new invite:
        if( $diff < 1 ) { // New if we are within 1 sec of original invite
                          // It is conceivable that a bot could still spam...
          require_once __DIR__ . '/send_email.php';
          require_once __DIR__ . '/get_email.php';
          require_once __DIR__ . '/get_username.php';
          $partner_email = get_email($orig_uuid);
          $partner_uname = get_username($orig_uuid);

          $htmlBody =  '<p>Hi!</p>'.
		       '<p>You have been invited to match names with '.
                       (!empty($partner_uname) ? $partner_uname : $partner_email)
                       .' on <a href="https://easymatch.waldocorp.com/about.php">Easy Match</a>.
                       This site is designed to help you find baby names that you and your partner
                       both like, <a href="https://easymatch.waldocorp.com/index.php">log on now</a>
                       to start matching names.</p>
                       <p> Thanks! </p>
		       <p> CatBot and the EasyMatch Team </p>
                       <p> You can <a href="https://easymatch.waldocorp.com/account.php">manage your email preferences</a> 
                       or <a href="easymatch.waldocorp.corm/unsubscribe.php">unsubscribe from all emails</a>';

          $textBody = "You have been invited to match names with ".
                       (!empty($partner_uname) ? $partner_uname : $partner_email)
                       ." on Easy Match (easymatch.waldocorp.com/about.php).
                       This site is deisgned to help you baby names that you and your partner
                       both like, log on now (easymatch.waldocorp.com) to start matching names. 
                       
                       Thanks! 
	               CatBot and the EasyMatch Team
                       You can manage you email preferences (easymatch.waldocorp.com/account.php) or 
                       unsubscribe from all emails (easymatch.waldocorp.com/unsubscribe.php).";


          $subj = "Baby Names Partner";
          $recipient = $email;
          send_email($htmlBody,$textBody,$subj,$recipient);
        }
      }
    } else {
      // This person is unknowningly 'responding' to an invitation:
      require_once __DIR__ . '/record_partner_choice.php';
      record_partner_choice($orig_uuid, $partner_uuid, true);
    }
}

