<!DOCTYPE html>
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
