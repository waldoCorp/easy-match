<?php
/**
 *    Copyright (c) 2020 Ben Cerjan, Lief Esbenshade
 *
 *    This file is part of Easy Match.
 *
 *    Easy Match is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    Easy Match is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.
**/

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
    $date = ' '.date("Y-m-d H:i:s"); // Add datetime so emails don't auto-collapse
    $mail->Subject = $subject.$date;
    $mail->Body    = $htmlBody;
    $mail->AltBody = $textBody;



   $mail->send();
   //echo 'Message Sent!\n';
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
  }
}
