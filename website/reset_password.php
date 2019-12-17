<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Reset Password</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Please Enter Your Email Address</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="0"></input>

      <div class="form-group w-50">
        <input id="login_email" class="form-control" aria-describedby="emailHelp" type="email" name="email" placeholder="Enter Email Address">
        <small id="emailHelp" class="form-text text-muted">If this email address has an account associated with it, you'll receive an email shortly to reset your password</small>
      </div>

      <button type="submit" class="btn btn-info" value="Submit" id="submit_btn" >Send Password Link</button>

    </fieldset>
  </form>

</div>
</main>

<?php include("footer.php"); ?>

</body>

</html>
