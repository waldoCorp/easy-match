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


<title>Update Your Password</title>
</head>

<body>
<?php
require_once $function_path .  'verify_password_token.php';
$_SESSION['pass_reset'] = false;

if( !empty($_SERVER['QUERY_STRING']) ) {
  // Parse the incoming query string:
  parse_str($_SERVER['QUERY_STRING'], $queryParams);

  $selector = $queryParams['selector'];
  $validator = $queryParams['validator'];
  $submitTarget = '';

  // Check for valid selector/validator format:
  if (strlen($selector) === 16 && strlen($validator) === 64) {
    // Valid length of selector
    $returnUUID = verify_password_token($selector,$validator);

    if (!empty($returnUUID)) {
      // Valid selector / validator pair -> we are good to go
      //echo $returnEmail . "\n";
      $submitTarget = './00_password_updated.php';
      $_SESSION['pass_reset'] = true;
      $_SESSION['update_uuid'] = $returnUUID;
    } else {
      //bad request, send them to time out, probably expired
      header('Location: ./password_timeout.php');
      //echo 'Expired?';
    }

  } else {
    //bad_request()
    //bad request, invalid query string format -- this is malicious (or in error)
    header('Location: ./password_timeout.php');
    //echo 'Bad Format?';
  }

} else {
        // We're definitely here by mistake
        //bad_request();
        //bad request, send them to time out
        header('Location: ./password_timeout.php');
        //echo 'All Wrong?';
}
?>


<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Please enter your new password</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="3"></input>

      <div class="form-group">
        <label for="password">Password</label>
        <input class="form-control" id="password1" name="passwd1" type="password" placeholder="hunter2">
      </div>

      <div class="form-group">
        <label for="password">Re-type Password</label>
        <input class="form-control" id="password2" name="passwd2" type="password" placeholder="*******">
      </div>

      <button type="submit" class="btn btn-primary" value="Submit" id="submit_btn" disabled>Update Password</button>

  </form>

</div>
</main>

<?php include("footer.php"); ?>

<!-- Custom JavaScript goes here -->
<script>
// Verify that passwords match (note that you CANNOT RELY ON THIS):
$('#password2').focusout(function() {
  var pass1 = $('#password1').val();
  var pass2 = $('#password2').val();

  if(pass1 == pass2) {
    $('#submit_btn').attr('disabled',false);
  }
});

</script>

</body>

</html>
