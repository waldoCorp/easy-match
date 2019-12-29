<?php

/**
 *
 * Script to automate testing of all functions to ensure that updates
 * have not broken any funcitonality (at least on the server-side).
 *
 * This script should be run against the testing database, to ensure
 * that everything is good before pushing to live.
 *
**/

/** Lots of Error Reporting: **/

error_reporting(E_ALL);
ini_set("display_errors",1);

/** Counter for total exceptions thrown" **/
$num_exceptions = 0;

/*********************
*                    *
*                    *
*    DB Connection:  *
*                    *
*                    *
**********************/

require_once __DIR__ . '/db_connect.php';

$db = db_connect();
$db->connection = null;

echo "Database connection open and closed.\n";


/*********************
*                    *
*                    *
*    Create Users:   *
*                    *
*                    *
**********************/

require_once __DIR__ . '/add_new_user.php';

// Fake Emails:
$email_1 = 'test1@waldocorp.com';
$email_2 = 'test2@waldocorp.com';

// Username:
$uname_1 = 'Unit Test Account 1';

// Password:
$pass_1 = bin2hex(random_bytes(10));

add_new_user($email_1,$pass_1,$uname_1);

echo "Added test user test1@waldocorp.com.\n";


/*********************
*                    *
*                    *
*     Get UUID:      *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_uuid.php';

$uuid_1 = get_uuid($email_1);

echo "Got UUID.\n";


/*********************
*                    *
*                    *
*     Get Email:     *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_email.php';

try {
  $test_email = get_email($uuid_1);
  if( $test_email !== $email_1 ) {
    throw new Exception("EXCEPTION! Returned invalid email\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}

echo "Got Email.\n";


/*********************
*                    *
*                    *
*    Set Filters:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/record_filters.php';


// Test other combinations here?
// Random could make getting names return 0 due to no names
$prefs = array(
          'gender'=>null,
          'first_letter'=>null,
          'last_letter'=>null,
          'popularity'=>null
         );

record_filters($uuid_1, $prefs);

echo "Recorded Filters.\n";


/*********************
*                    *
*                    *
*      Get Names:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_names.php';

$names = get_names($uuid_1,25);

echo "Got ". count($names)." Names.\n";


/*********************
*                    *
*                    *
*     Rate Names:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/record_selection.php';

$name_0 = $names[0]['name'];
$name_1 = $names[1]['name'];

record_selection($uuid_1, $name_0, true);
record_selection($uuid_1, $name_1, false);

echo "Rated two names (one yes, one no).\n";



/*********************
*                    *
*                    *
*  Invite Partner:   *
*                    *
*                    *
**********************/

require_once __DIR__ . '/invite_partner.php';

// User 1 invites User 2 (user 2 not yet created):
invite_partner($email_2, $uuid_1);

echo "Invited test2@waldocorp.com.\n";


/*********************
*                    *
*        Get         *
*    Invitations:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_invitations.php';


$uuid_2 = get_uuid($email_2);


try {
  $inv = get_invitations($uuid_2);
  if( key($inv) !== $uuid_1 ) {
    throw new Exception("EXCEPTION! Returned a bad uuid from the inviation\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}


/*********************
*                    *
*    Respond To      *
*    Invitation:     *
*                    *
*                    *
**********************/

require_once __DIR__ . '/record_partner_choice.php';

// First Deny, then Accept Invitation:
record_partner_choice($uuid_2, $uuid_1, false);
record_partner_choice($uuid_2, $uuid_1, true);

echo "Responded to invitation.\n";


/*********************
*                    *
*                    *
* Get Matched Names: *
*                    *
*                    *
**********************/


$names_2 = get_names($uuid_2,25);
$name_2 = $names_2[0]['name'];

try {
  if( $name_2 !== $name_0 ) {
    throw new Exception("EXCEPTION! Not getting names correctly --
                        name_0 = ".$name_0." and
                        name_2 = ".$name_2."\n");
 }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}

echo "get_names returned the correct name for a partner.\n";


/*********************
*                    *
*      Approve       *
*   Matched Name:    *
*                    *
*                    *
**********************/

record_selection($uuid_2, $name_2, true);


echo "Partner approved the same name as original user.\n";


/*********************
*                    *
*      Get Name      *
*     Match List:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_matching_names_list.php';

$matches = get_matching_names_list($uuid_1,$uuid_2);

try {
  if( $matches[0] !== '*'.$name_2 ) {
    throw new Exception("EXCEPTION! Not finding name match correctly\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}

echo "Matched name: ".$matches[0].".\n";


/*********************
*                    *
*    Get Selected    *
*     Names List:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_selections.php';

$selections = get_selections($uuid_1);


try {
  if( count($selections) !== 2 ) {
    throw new Exception("EXCEPTION! Wrong number of selections returned\n");
  }

  // Since the return array isn't in a consistent order, we need to find
  // where our name is
  $key = array_search($name_1,array_column($selections,'name'));

  if( $selections[$key]['name'] !== $name_1 ) {
    throw new Exception("EXCEPTION! Selection is not what it should be\n");
  }

  if( $selections[$key]['selected'] !== false ) {
    throw new Exception("EXCEPTION! The first name has the wrong selection property\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}

echo "Got Selections.\n";


/*********************
*                    *
*    Update Comm.    *
*    Preferences:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/record_comm_prefs.php';

$prefs = array(
           'allComm'=>true,
           'partnerComm'=>null,
           'promotionComm'=>null,
           'noComm'=>null,
         );

record_comm_prefs($uuid_1,$prefs);

echo "Updated Communications preferences.\n";


/*********************
*                    *
*    Update Data     *
*    Preference:     *
*                    *
*                    *
**********************/

require_once __DIR__ . '/record_data_pref.php';

record_data_pref($uuid_1,true);

echo "Updated Data-Sharing preference.\n";


/*********************
*                    *
*     Get Data       *
*    Preference:     *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_data_pref.php';

try {
  $data = get_data_pref($uuid_1);
  if( $data !== true ) {
    throw new Exception("EXCEPTION! Returning wrong data sharing preferencem\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}

/*********************
*                    *
*     Get Comm.      *
*    Preference:     *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_comm_prefs.php';

$expected = array(
            "all_comm"=>true,
            0=>true,
            "none"=>false,
            1=>false,
            "functional"=>false,
            2=>false,
            "promotional"=>false,
            3=>false,
            );
try {
  $comm = get_comm_prefs($uuid_1);
  if( $comm !== $expected ) {
    throw new Exception("EXCEPTION! Returning bad communications preferences\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}


/*********************
*                    *
*                    *
*   Get Partners:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_partners.php';

try {
  $partners = get_partners($uuid_1);
  if( key($partners) !== $uuid_2 ) {
    throw new Exception("EXCEPTION! Not finding partner correctly\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}

/*********************
*                    *
*                    *
*   Get Username:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_username.php';

try {
  $uname = get_username($uuid_1);
  if( $uname !== $uname_1 ) {
    throw new Exception("EXCEPTION! Not finding correct username\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMEssage();
}

/*********************
*                    *
*                    *
*   Send Password:   *
*                    *
*                    *
**********************/

require_once __DIR__ . '/send_password_link.php';

send_password_link($email_1);


/*********************
*                    *
*                    *
*   Update Login:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/update_last_login.php';

update_last_login($uuid_1);


/*********************
*                    *
*                    *
*  Update Password:  *
*                    *
*                    *
**********************/

require_once __DIR__ . '/update_password.php';

$new_pass = bin2hex(random_bytes(10));
update_password($uuid_1, $new_pass);


/*********************
*                    *
*                    *
*  Verify Password:  *
*                    *
*                    *
**********************/

require_once __DIR__ . '/password_check.php';

try {
  if( !password_check($email_1, $new_pass) ) {
    throw new Exception("EXCEPTION! Password not checking and/or updating correctly\n");
  }
} catch (Exception $e) {
  ++$num_exceptions;
  echo $e->getMessage();
}


/*********************
*                    *
*                    *
* Remove Test Users: *
*                    *
*                    *
**********************/

require_once __DIR__ . '/delete_account.php';

delete_account($uuid_1);
delete_account($uuid_2);

echo "Removed Test Users.\n";

/*********************
*                    *
*                    *
*    Final Score:    *
*                    *
*                    *
**********************/

echo "=======================\n";
echo "=======================\n";

if( $num_exceptions !== 0 ) {
  echo("There were ".$num_exceptions." exceptions thrown!\n");
} else {
  echo "Code passed all tests successfully.\n";
}
