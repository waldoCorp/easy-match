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


<title>Create an Account</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Create an Account</h2>

  <form action="./endpoints/user_endpoint.php" method="post">

    <input type="hidden" name="type" value="0"></input>

    <div class="form-group w-50">
        <label for="email">Email Address</label>
        <input id="login_email" class="form-control" type="email" name="email" aria-describedby="emailHelp" placeholder="Enter Email">
        <small id="emailHelp" class="form-text text-muted">An email will be sent to verify that you own this email and to set up a password.</small>
    </div>

    <div>
      <small id='errorEmailText' class='text-danger'></small>
    </div>

    <div class="form-group w-50">
        <label for="uname">Username</label>
        <input id="username" class="form-control" aria-describedby="unameHelp" type="text" name="uname" placeholder="Your Name Here">
        <small id="unameHelp" class="form-text text-muted">This username is only used to identify yourself to other users &mdash; it is optional.</small>
    </div>

    <div class="form-check">
      <input id="privacyPolicy" class="form-check-input" type="checkbox" name="privacy" value="privacy">
      <label class="form-check-label" for="privacyPolicy">
        I have read and agree to the <a href="privacy.php">privacy policy</a>.
      </label>
    </div>

    <div>
      <small id='errorPrivacyText' class='text-danger'></small>
    </div>

    <button type="submit" class="btn btn-primary" value="Submit" id="submit_btn" disabled >Create Account</button>

  </form>

</div>
</main>

<?php include("footer.php"); ?>


<script>
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');

if( error == 'email' ) {
  $('#errorEmailText').html('Bad Email Address, please try again');
  $('#login_email').addClass("is-invalid");
}

if( error == 'privacy' ) {
  $('#errorPrivacyText').html('You must accept the privacy policy to use this site');
  $('#privacyPolicy').addClass("is-invalid");
}



// Enable/disable submit if privacy policy agreed to
$('#privacyPolicy').change(function() {
  if ($(this).is(':checked')) {
    $('#submit_btn').prop('disabled',false);
  } else {
    $('#submit_btn').prop('disabled',true);
  }
});


</script>
</body>

</html>
