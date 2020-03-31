<!DOCTYPE html>
<!--
 -    Copyright (c) 2020 Ben Cerjan, Lef Esbenshade
 -
 -    This file is part of Easy Match.
 -
 -    Easy Match is free software: you can redistribute it and/or modify
 -    it under the terms of the GNU Affero General Public License as published by
 -    the Free Software Foundation, either version 3 of the License, or
 -    (at your option) any later version.
 -
 -    Easy Match is distributed in the hope that it will be useful,
 -    but WITHOUT ANY WARRANTY; without even the implied warranty of
 -    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 -    GNU Affero General Public License for more details.
 -
 -    You should have received a copy of the GNU Affero General Public License
 -    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.
-->
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Unsubscribed from Easy Match</title>
</head>

<?php

require_once $function_path . 'get_email.php';
require_once $function_path . 'verify_unsubscribe_token.php';
require_once $function_path . 'record_comm_prefs.php';

$token = $_GET['token'];
$uuid = verify_unsubscribe_token($token);
$email = get_email($uuid);

$comm_prefs = array('noComm'=>true);
if( !is_null($uuid) ) {
  record_comm_prefs($uuid, $comm_prefs);
}
?>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">


  <?php if( !is_null($email) ) { ?>
   <div class="alert alert-danger">
   <h2>Email Removed</h2>

   <p>Your email address (<?php echo htmlspecialchars($email); ?>) has been unsubscribed from all emails from Easy Match.
      If you would like to change this at a later date, create an account and re-subscribe
      to emails.</p>
  <?php } else { ?>
   <div class ="alert alert-danger">
   <h2>Email Not Removed</h2>

   <p>That link is either bad or expired. To adjust your email preferences please log in
      and adjust your settings <a href="account.php">on your account page</a>.</p>
  <?php } ?>

  </div>

</div>
</main>

<?php include("footer.php"); ?>

</body>

</html>
