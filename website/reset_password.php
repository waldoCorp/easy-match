<!DOCTYPE html>
<!--
 -    Copyright (c) 2020 Ben Cerjan, Lief Esbenshade
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


<title>Reset Password</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Please Enter Your Email Address</h2>
  <form action="./endpoints/user_endpoint.php" method="post">
    <input type="hidden" name="type" value="0"></input>
    <input type="hidden" name="privacy" value="1"></input>

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
