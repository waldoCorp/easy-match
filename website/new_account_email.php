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


<title>New Account Created</title>
</head>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">

  <div class="alert alert-success">
   <h2>Password Link Sent</h2>

   <p>Please look for an email from
     <a class="alert-link" href="mailto:catbot@waldocorp.com">catbot@waldocorp.com</a>
     to finish setting up your account or change your password.</p>
   <p><a class="alert-link" href="index.php">Back to the homepage</a></p>
  </div>

</div>
</main>

<?php include("footer.php"); ?>

</body>

</html>
