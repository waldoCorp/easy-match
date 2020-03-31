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

<title>Data Download</title>


</head>
<body>
<?php include("header.php"); ?>

<?php
require_once $function_path . 'create_data_token.php';
$uuid = $_SESSION['uuid'];
$token = create_data_token($uuid);

?>

<main role="main">
<div class="container">

<iframe id="downloadData" src="./shiny/downloadData?token=<?= $token; ?>"  style="border: none; width: 100%; height: 850px" frameborder="0"></iframe>

</div>
</main>

<?php include("footer.php"); ?>
</body>
</html>
