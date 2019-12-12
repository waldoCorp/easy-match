<!DOCTYPE html>
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
  <h2>Please enter your Email and Password</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="3"></input>
    <fieldset>
      <legend>New Password Please</legend>

      <p>
        <label for="password">Password</label>
        <input id="password1" name="passwd1" type="password">
      </p>

      <p>
        <label for="password">Re-type Password</label>
        <input id="password2" name="passwd2" type="password">
      </p>

      <p>
        <button type="submit" value="Submit" id="submit_btn" disabled>Update Password</button>
      </p>

    </fieldset>
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
