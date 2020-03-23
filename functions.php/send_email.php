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

require_once __DIR__ . '/create_unsubscribe_token.php';
require_once __DIR__ . '/get_uuid.php';

function send_email($htmlBody,$textBody,$subject,$recipient) {
  //$mail = new PHPMailer(true); // Use this one to enable debug output
  $mail = new PHPMailer();

  try {

    //Recipients
    $mail->setFrom('catbot@waldocorp.com', 'CatBot <noreply>');
    $mail->addReplyTo('noreply@waldocorp.com');
    $mail->addAddress($recipient);     // Add a recipient

    // Add signoff message
    $unsub_link = create_unsubscribe_token(get_uuid($recipient));

    $htmlBody = $htmlBody.'
    <p> Thanks! </p>
    <p> CatBot and the Easy Match Team </p>
    <p> You can <a href="https://easymatch.waldocorp.com/account.php">manage your email preferences</a>
    or <a href='. $unsub_link .'>unsubscribe from all emails</a>';

    $textBody = $textBody.'
    Thanks!
    CatBot and the Easy Match Team
    You can manage you email preferences (easymatch.waldocorp.com/account.php) or
    unsubscribe from all emails ('. $unsub_link .').';

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody;
    $mail->AltBody = $textBody;



   $mail->send();
   //echo 'Message Sent!\n';
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
  }
}
