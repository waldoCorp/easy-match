<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Demo Page</title>
</head>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>Welcome to Baby Names Match!</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <fieldset>
      <legend>Sign in (or <a href="new_account.php">create account</a>)</legend>
      <p>
        <label for="email">Email</label>
        <input name="email" id="login_email" type="email" placeholder="Enter Email Address">
      </p>

      <p>
        <label for="password">Password</label>
        <input name="pass" id="password" type="password">
      </p>

      <p>
        <button type="submit" value="Submit">Log In</button>
      </p>

    </fieldset>
  </form>


  <form>
    <fieldset>
      <legend>Add a friend to compare matches!</legend>
      <p>
        <label for="email">Email</label>
        <input id="friend_email" type="email" placeholder="Enter Email Address of a Partner">
      </p>

      <p>
        <button type="button" id="add_friend" value="Submit">Add Friend!</button>
      </p>

    </fieldset>
  </form>

</div>


<!-- Custom JavaScript goes here -->
<script>
$('#add_friend').click(function() {
  // AJAX request to send an invite email.
  // Send email if no email sent already / they aren't a user yet
  // If they have received an email recently, no email (notice?)
  // If they are already in the system, tell the user that too
  // Also, disable the button until the user changes the email address

  // Testing code:
  // console.log() is super useful, it logs things to the developer
  // console so you can see what's going on with the site. You can log variables
  // console.log(var1) or strings (like I'm doing here)
  console.log('Clicked!');

});
</script>

</body>

</html>
