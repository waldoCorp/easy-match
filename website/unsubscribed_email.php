<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Email Unsubscribed</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">

  <div class="alert alert-danger">
   <h2>Email Removed</h2>

   <p>Your email address (<?php echo htmlspecialchars($_GET['email']); ?>) has been unsubscribed from all emails from Easy Match.
      If you would like to change this at a later date, create an account and re-subscribe
      to emails.</p>
  </div>

</div>
</main>

<?php include("footer.php"); ?>

</body>

</html>
