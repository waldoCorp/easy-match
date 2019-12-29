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

get_email($uuid_1);

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
*    Respond To      *
*    Invitation:     *
*                    *
*                    *
**********************/

require_once __DIR__ . '/record_partner_choice.php';

$uuid_2 = get_uuid($email_2);

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

if( $name_2 !== $name_0 ) {
  exit('Not getting names correctly.
        name_0 = '.$name_0 .' and
        name_2 = '.$name_2.'\n');
}

echo "Got a matched name.\n";


/*********************
*                    *
*      Approve       *
*   Matched Name:    *
*                    *
*                    *
**********************/

record_selection($uuid_2, $name_2, true);


echo "Matched on a name.\n";


/*********************
*                    *
*      Get Name      *
*     Match List:    *
*                    *
*                    *
**********************/

require_once __DIR__ . '/get_matching_names_list.php';

$matches = get_matching_names_list($uuid_1,$uuid_2);

if( count($matches) !== 1 ) {
  exit('Not finding match correctly.\n');
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

if( count($selections) !== 2 ) {
  exit('Not getting correct number of selections.\n');
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
           'partnerComm'=>false,
           'promotionComm'=>false,
           'noComm'=>false,
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
*                    *
* Remove Test Users: *
*                    *
*                    *
**********************/

require_once __DIR__ . '/delete_account.php';

delete_account($uuid_1);
delete_account($uuid_2);

echo "Removed Test Users.\n";

