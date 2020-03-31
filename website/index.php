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

<?php
// If we're logged in, we shouldn't be on this page.
if( $_SESSION['login'] ) {
  header('Location: ./show_names.php');
}
?>

<?php include("./resources.php"); ?>


<title>Easy Match!</title>

</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Welcome to Easy Match!</h2>

  <form action="./endpoints/user_endpoint.php" method="post">
   <div class="form-row">
    <input type="hidden" name="type" value="1"></input>

      <legend>Sign in (or <a href="new_account.php">create account</a> / <a href="reset_password.php">reset password</a>)</legend>

      <div class="form-group col-md-5">
        <label for="email">Email</label>
        <input name="email" id="email" class="form-control" type="email" placeholder="Enter Email Address">
      </div>
   </div>
   <div class="form-row">
      <div class="form-group col-md-5">
        <label for="password">Password</label>
        <input name="passwd" class="form-control" id="password" type="password" placeholder="Super Secret">
      </div>
   </div>
   <div class="form-row">
      <div class="form-group">
      <small id='errorText' class="text-danger"></small>
      </div>
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
