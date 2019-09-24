<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap Sourcing, CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<!-- Bootstrap Sourcing, jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>



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
