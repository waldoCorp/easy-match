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

      <legend>Sign in (or <a href="new_account.php">create account</a> / <a href="reset_password.php">reset password</a>)</legend>

      <div class="form-group w-25">
        <label for="email">Email</label>
        <input name="email" id="email" class="form-control" type="email" placeholder="Enter Email Address">
      </div>

      <div class="form-group w-25">
        <label for="password">Password</label>
        <input name="passwd" class="form-control" id="password" type="password" placeholder="Super Secret">
      </div>

      <div>
      <small id='errorText' class="text-danger"></small>
      </div>
<br>
      <button type="submit" class="btn btn-primary" value="Submit">Log In</button>
  </form>

</div>
</main>
<?php include("footer.php"); ?>

<script>
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');

if( error == 'bad_login' ) {
  $('#errorText').html('Bad Email Address or Password, please try again');
  $('#email').addClass("is-invalid");
  $('#password').addClass("is-invalid");
}

</script>

</body>

</html>
