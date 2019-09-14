<?php
/**
 * Function to automate email sending.
 * Takes a recipient, html body, flat text body, and subject line as inputs
 *
 * example usage:
 * require_once './functions.php/send_email.php';
 *
 * $success = send_email($htmlBody,$textBody,$subject,$recipient);
 *
 * @author Ben Cerjan
 * @param $htmlBody : html-formatted email
 * @param $textBody :  flat text formatted email
 * @param $subject : Flat-text subject material
 * @param $recipient : email address
 *
 * Returns an array with : (success => TRUE, message => "ID") OR
 * (success = FALSE, message => "Error Message")
**/


use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;


// Path to autoloader for AWS SDK
define('REQUIRED_FILE', "/srv/nameServer/vendor/autoload.php");

// Region:
define('REGION','us-west-2');

// Charset
define('CHARSET','UTF-8');

// Specify Sender
define('SENDER', 'setup@easydivider.com');

// 'Use' statements need to be outside the function call
require REQUIRED_FILE;




function send_email($htmlBody,$textBody,$subject,$recipient) {
	// Pull our email client keys:
	$keyfile = "/srv/nameServer/email-sdk-keys.txt";
	$lines = file($keyfile);

	foreach ( $lines as $line ) {
		$var = explode(',', $line, 2);
		$arr[$var[0]] = trim($var[1]);
	}

	$access_key = $arr['accesskeyid'];
	$secret_key = $arr['secretkey'];

	$ret_array = array('success' => false,
			'message' => 'No Email Sent'
			);

	// Recipient
	// Check if valid?
	//define('RECIPIENT', $recipient);

	// Configuration settings:
	//define('CONFIGSET','XXXXX');


	// Set subject:
	//define('SUBJECT',$subject);

	// Body:
	//define('HTMLBODY',$htmlBody);

	//define('TEXTBODY',$textBody);



	$client = SesClient::factory(array(
		'version' => 'latest',
		'region' => REGION,
		'credentials' => array(
			'key' => $access_key,
			'secret' => $secret_key
		)
	));


	try {
		$result = $client->sendEmail([
			'Destination' => [
				'ToAddresses' => [
					$recipient,
				],
			],
			'Message' => [
				'Body' => [
					'Html' => [
						'Charset' => CHARSET,
						'Data' => $htmlBody,
					],
					'Text' => [
						'Charset' => CHARSET,
						'Data' => $textBody,
					],
				],
				'Subject' => [
					'Charset' => CHARSET,
					'Data' => $subject,
				],
			],
			'Source' => SENDER,

			//'ConfigurationSetName' => CONFIGSET // NEED TO ADD COMMA ABOVE
		]);

		$messageId = $result->get('MessageId');
		$ret_array['success'] = true;
		$ret_array['message'] = $messageId;
		echo("Email sent! Message ID: $messageId" . "\n");
	} catch (SesException $error) {
		echo("The email was not sent. Error message: " . $error->getAwsErrorMessage() . "\n");
		$ret_array['message'] = $error->getAwsErrorMessage();
		$ret_array['errorCode'] = $error->getAwsErrorCode();
		$ret_array['type'] = $error->getAwsErrorType();
		$ret_array['response'] = $error->getResponse();
		$ret_array['statusCode'] = $error->getStatusCode();
		$ret_array['isConnectionError'] = $error->isConnectionError();
	}

	return $ret_array;
}
