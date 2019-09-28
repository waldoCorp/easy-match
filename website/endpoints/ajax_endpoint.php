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
			case "getNames": get_names(); break;
			case "email_check": unique_email(); break;
			case "send_password_token": send_password_token(); break;
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
	//require_once '/srv/nameServer/functions.php/name_rank.php'; //CHANGE FUNCTION NAME
	$return = $_POST;
	$is_good = $return['goodName'];
	$name = $return['name'];
	$email = $return['email'];
	//name_rank($email, $name, $is_good);

}

function get_names() {
	//require_once '/srv/nameServer/functions.php/generate_names.php'; //CHANGE FUNCTION NAME
	$data = $_POST;
	$email = $data['email'];
	//$cur_names = $data['list']; // Maybe?
	//$new_names = generate_names($email,$cur_names);
	$new_names = array('David','Erin','Frank');
	echo json_encode($new_names);

}

function unique_email() {
	require_once '/srv/nameServer/functions.php/check_email.php';
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

	$email = $_POST["email"];
	$recovery_table = 'account_recovery';
	$user_table = 'users';

	// Check for valid email:
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// Check if an existing user:
		require_once '/srv/nameServer/functions.php/check_email.php';
		$exists = check_email($user_table,$email);

		require_once '/srv/nameServer/functions.php/send_email.php';
		if ($exists) {
			// for testing:
			$_SESSION['token_sent'] = true;

			require_once '/srv/nameServer/functions.php/create_password_token.php';

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

// ------------------------------------
// Helper functions not directly accessible through AJAX
