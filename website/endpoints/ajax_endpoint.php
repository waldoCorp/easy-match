<?php
/*
 * Endpoint for handling AJAX requests and returning values as needed.
 *
*/

// NEED TO ADD VERIFICATION FOR ALL FUNCTIONS
// i.e. make sure the request is allowed by the user doing the requesting


if (is_ajax()) {
	if (isset($_POST["action"]) && !empty($_POST["action"])) {
		$action = $_POST["action"];
		switch($action) {
			case "nameRecord": name_record(); break;
			case "getNames": new_names(); break;
			case "inviteFriend": invite_friend(); break;
			case "partnerSelect": partner_select(); break;
			case "partnerResponse": partner_response(); break;
			case "email_check": unique_email(); break;
			case "unameUpdate": uname_update(); break;
			case "send_password_token": send_password_token(); break;
			case "preferencesRecord": pref_record(); break;
			case "communicationsUpdate": comm_record(); break;
			case "deleteAccount": delete_acc(); break;
		}
	}
}

// Function to check if a real AJAX request:
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function test_function() {
	$return = $_POST;
	// Do stuff?

	$return["json"] = json_encode($return);
	echo json_encode($return);
}

function name_record() {
	global $function_path;
        require_once $function_path . 'record_selection.php';
	$return = $_POST;
	$is_good = ($return['goodName'] == 'yes') ? true : false;
	$name = $return['name'];

	$uuid = $_SESSION['uuid'];
	record_selection($uuid, $name, $is_good);

}

function uname_update() {
	global $function_path;
	require_once $function_path . 'update_username.php';
        $uuid = $_SESSION['uuid'];
	$uname = $_POST['uname'];
	update_username($uuid,$uname);
}

function invite_friend() {
	global $function_path;
        require_once $function_path . 'invite_partner.php';
	$new_email = $_POST['new_email'];
	$orig_uuid = $_SESSION['uuid']; // Maybe use orig_email instead?

	if( filter_var($new_email, FILTER_VALIDATE_EMAIL) ) {
		invite_partner($new_email,$orig_uuid);
	}
}

function partner_select() {
	global $function_path;
        require_once $function_path . 'get_uuid.php';
	$return = $_POST;

	// Make sure it's an email address:
	//if (filter_var($return["email"], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['partner_email'] = $return['partner_email'];
	//}
	echo json_encode('Ready');
}

function partner_response() {
	global $function_path;
        require_once $function_path . 'record_partner_choice.php';
	$return = $_POST;
	$uuid = $_SESSION['uuid'];
	$partner_uuid = $return['partner_uuid'];
	$status = $return['status'];
	if( $status == 'accept' ) {
		$keep_partner = true;
	} else {
		$keep_partner = false;
	}
	record_partner_choice($uuid,$partner_uuid,$keep_partner);

}

function new_names() {
	global $function_path;
	require_once $function_path . 'get_names.php';
	$data = $_POST;
	$uuid = $_SESSION['uuid'];

	$n = 15; // Number of names to return

	$new_names = get_names($uuid,$n);

	echo json_encode($new_names);

}

function unique_email() {
	global $function_path;
	require_once $function_path . 'check_email.php';
	$return = $_POST;
	$user_table = 'users';
	// Note that true means we are not unique
	$isUnique = check_email($user_table,$return["email"]);

	// Make sure this is a valid email address:
	if (!filter_var($return["email"], FILTER_VALIDATE_EMAIL)) {
		$isUnique = true;
	}

	// Save temp email for later
	if (!$isUnique) {
		// If we have a good email address, save it for later:
		$_SESSION["temp_email"] = $return["email"];
	}
	echo json_encode($isUnique);

}

function send_password_token() {
	global $function_path;

	$email = $_POST["email"];
	$recovery_table = 'account_recovery';
	$user_table = 'users';

	// Check for valid email:
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// Check if an existing user:
		require_once $function_path . 'check_email.php';
		$exists = check_email($user_table,$email);

		require_once $function_path . 'send_email.php';
		if ($exists) {
			// for testing:
			$_SESSION['token_sent'] = true;

			require_once $function_path . 'create_password_token.php';

			$urlToEmail = create_password_token($recovery_table,$email);
			// Send Email:
			$_SESSION['test'] = $urlToEmail;

			$subject = 'NameSelector Password Reset';
			$htmlBody = '<p>Below is a password reset link for the account associated with this email address:
				<br> <a href='. $urlToEmail . '>Link</a>';
			$txtBody = $urlToEmail;
			$result = send_email($htmlBody,$txtBody,$subject,$email);
			//$result = send_email($htmlBody,$txtBody,$subject,'success@simulater.amazonses.com');

			echo json_encode($result);
		}
	}


}

function pref_record() {
	global $function_path;

        require_once $function_path . 'record_filters.php';
	$preferences = $_POST;


	$uuid = $_SESSION['uuid'];
	record_filters($uuid, $preferences);
}

function comm_record() {
	global $function_path;

        require_once $function_path . 'record_comm_prefs.php';
	$preferences = $_REQUEST['commPref'];

        $pref_arr = array();
        foreach( $preferences as $pref ) {
          $pref_arr[$pref] = true;
        };

	$uuid = $_SESSION['uuid'];
	record_comm_prefs($uuid, $pref_arr);
        echo json_encode($pref_arr);
}


function delete_acc() {
	global $function_path;

	require_once $function_path . 'delete_account.php';
	$uuid = $_SESSION['uuid'];
	delete_account($uuid);
	// Also clear SESSION variables:
	session_regenerate_id(true);
	$_SESSION = array();
}

// ------------------------------------
// Helper functions not directly accessible through AJAX
