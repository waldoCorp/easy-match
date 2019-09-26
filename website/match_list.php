<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Matching Names</title>
</head>

<?php
// Region to set up PHP stuff

//$names = get_matching_names_list($email1,$email2);
$names = array('Alice','Bob','Charlie');

shuffle($names); // Randomizing seems like as good a call as any here
// Maybe should sort alphabetically instead, but I'm not sure...

?>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>List of Names you and XXXX Agree On</h2>

  <?php foreach($names as $name) { ?>
  <div class="row">
    <div class="col-sm">
      <?php echo(htmlspecialchars($name)); ?>
    </div>
  </div>
  <?php } ?>
</div>

</body>

</html>
