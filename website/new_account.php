<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Create an Account / Reset Password</title>
</head>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>Please Enter Your Email Address</h2>
  <p>An email will be sent allowing you to set (or reset) password for this email address</p>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="0"></input>
    <fieldset>
      <legend>Information Please</legend>
      <p>
        <label for="email">Email</label>
        <input id="login_email" type="email" name="email" placeholder="Enter Email Address">
      </p>

      <p>
        <button type="submit" value="Submit" id="submit_btn" >Send Password Link</button>
      </p>

    </fieldset>
  </form>

</div>

</body>

</html>
