<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Name Match!</title>

</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Welcome to Baby Names Match!</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="1"></input>
    <fieldset>
      <legend>Sign in (or <a href="new_account.php">create account / reset password</a>)</legend>
      <p>
        <label for="email">Email</label>
        <input name="email" id="login_email" type="email" placeholder="Enter Email Address">
      </p>

      <p>
        <label for="password">Password</label>
        <input name="passwd" id="password" type="password">
      </p>

      <p>
        <button type="submit" value="Submit">Log In</button>
      </p>

      <p id='errorText' class="text-danger"></p>
    </fieldset>
  </form>

</div>
</main>
<?php include("footer.php"); ?>

<script>
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');

if( error == 'bad_login' ) {
  $('#errorText').html('Bad Password or Username, please try again');
}

</script>

</body>

</html>
