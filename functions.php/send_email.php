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


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Path to autoloader
require __DIR__ . '/../vendor/autoload.php';

function send_email($htmlBody,$textBody,$subject,$recipient) {
  //$mail = new PHPMailer(true); // Use this one to enable debug output
  $mail = new PHPMailer();

  // Load credentials:
  require __DIR__ . '/../email_config.php';

  try {
    // Server Settings:
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $GUSER;                     // SMTP username
    $mail->Password   = $GPWD;                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('setup@waldocorp.com', 'WaldoCorp Setup <noreply>');
    $mail->addAddress($recipient);     // Add a recipient
    /*
    $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
    */

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody;
    $mail->AltBody = $textBody;

   $mail->send();
   echo 'Message Sent!\n';
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
  }
}
