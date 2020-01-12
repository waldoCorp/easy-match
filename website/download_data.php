<!DOCTYPE html>
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

<iframe id="downloadData" src="./shiny/downloadData?uuid=<?= $uuid ?>"  style="border: none; width: 100%; height: 850px" frameborder="0"></iframe>

</div>
</main>

<?php include("footer.php"); ?>
</body>
</html>
