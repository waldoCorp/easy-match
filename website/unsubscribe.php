<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Unsubscribe from Easy Match</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Unsubscribe from Easy Match</h2>

  <form action="./endpoints/user_endpoint.php" method="post">

    <input type="hidden" name="type" value="4"></input>

    <div class="form-group w-50">
        <label for="email">Email Address</label>
        <input id="login_email" class="form-control" type="email" name="email" aria-describedby="emailHelp" placeholder="Enter Email">
        <small id="emailHelp" class="form-text text-muted">This email address will no longer recieve emails from Easy Match (change this setting on <a href='https://easymatch.waldocorp.com/account.php'>your account page </a> ).</small>
    </div>

    <div>
      <small id='errorEmailText' class='text-danger'></small>
    </div>

    <button type="submit" class="btn btn-danger" value="Submit" id="submit_btn">Unsubscribe</button>

  </form>

  <br>

 <div id="emailAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style='display:none;'>
 </div>


</div>
</main>

<?php include("footer.php"); ?>

<script>
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');

if( error == 'email' ) {
  $('#errorEmailText').html('Bad email address, please try again');
  $('#login_email').addClass("is-invalid");
}
</script>

</body>

</html>
