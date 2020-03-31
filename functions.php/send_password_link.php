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
 * Function to send a password setting / reset link
 *
 * Use of function:
 * require_once '../send_password_link.php';
 *
 *
 * send_password_link($email);
 *
 * @param $email : Email for the user
 *
 * @return No return, silent execution
**/

function send_password_link($email) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';

    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // email to lower case
    $email = strtolower($email);

    // First, we need to add the new user to the database
    //require_once __DIR__ . '/add_new_user.php';

    // Giving them a fake password (if they do not already have one)
    //$pass = bin2hex(random_bytes(5));
    //add_new_user($email, $pass); // This will silently fail if the user already exists

    // Now, get the UUID of the new user:
    require_once __DIR__ . '/get_uuid.php';
    $uuid = get_uuid($email);

    // Next, get the URL to email to the user:
    require_once __DIR__ . '/create_password_token.php';
    $urlToSend = create_password_token($uuid);

    // Finally, email that bad boy out:
    require_once __DIR__ . '/send_email.php';

    $subject = 'Easy Match Password Re/Set';
    $html_body =  '<p>Hi!</p>

                  <p>We recieved a password request for your account.
                  Follow  <a href="'. $urlToSend .'">this link</a> to
                  (re)set your password for EasyMatch.
                  If you did not request a password reset, please ignore this email.
                  The password reset link is only valid for the next hour.</p>';

    $txt_body = 'Hi! We received a password request for your account.
                 Follow \n' . $urlToSend
                 .' to (re)set your password for EasyMatch.
                 If you did not request a password reset, please ignore this email.
                 The password reset link is only valid for the next hour.';

    send_email($html_body,$txt_body,$subject,$email);
}
