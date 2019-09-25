<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Edit Name Selections</title>
</head>

<?php
// Region to set up PHP stuff

//$selections = get_name_selections
$selections = array(
		1 => array(
			'name' => 'Name 1',
			'selected' => 'Yes'
			),
		2 => array(
			'name' => 'Name 2',
			'selected' => 'No'
			)
		);

// Loop counter:
$i = 0;

?>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>Change what you think about a name</h2>

<?php foreach($selections as $selection) { $i++?>
  <div class="row">
    <div class="col-sm" name="name">
      <?php echo(htmlspecialchars($selection['name'])); ?>
    </div>
    <div class="col-sm" name="selected">
      <?php echo($selection['selected']); ?>
    </div>
    <div class="col-sm">
      <button type="button" class="swap_btn">Swap</button>
    </div>
  </div>
<?php } ?>
</div>


<!-- Custom JavaScript goes here -->
<script>
$('.swap_btn').click(function() {
  // AJAX request to swap name choice in DB.

  console.log('Clicked!');

});
</script>

</body>

</html>
