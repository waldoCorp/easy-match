<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Create an Account</title>
</head>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>Please enter your Email and Password</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="0"></input>
    <fieldset>
      <legend>Information Please</legend>
      <p>
        <label for="email">Email</label>
        <input id="login_email" type="email" name="email" placeholder="Enter Email Address">
      </p>

      <p>
        <label for="password">Password</label>
        <input id="password1" name="passwd1" type="password">
      </p>

      <p>
        <label for="password">Re-type Password</label>
        <input id="password2" name="passwd2" type="password">
      </p>

      <p>
        <button type="submit" value="Submit" id="submit_btn" disabled>Create Account</button>
      </p>

    </fieldset>
  </form>

</div>


<!-- Custom JavaScript goes here -->
<script>
$('#login_email').focusout(function() {
  // AJAX request to check if email is in use already.
  // Show an error if it is (and disable 'Submit' button)

  // Testing code:
  // console.log() is super useful, it logs things to the developer
  // console so you can see what's going on with the site. You can log variables
  // console.log(var1) or strings (like I'm doing here)
  console.log('Clicked!');

});

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
