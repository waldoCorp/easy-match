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

//$selections = get_name_selections($email or $uid);
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

?>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>Change what you think about a name</h2>

<?php foreach($selections as $selection) { ?>
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
  // Find Current status:
  var cur_field = $(this).closest("div.row").find("[name='selected']");
  var cur_text = cur_field.text().trim();

  // AJAX request to swap name choice in DB.

  // Show the swap on the page:
  if( cur_text == 'No' ) {
    cur_field.text('Yes');
    updateNameStatus('yes',cur_text);
  } else {
    cur_field.text('No');
    updateNameStatus('no',cur_text);
  }


  //find('.row').closest("name=['selected']")

});

function updateNameStatus(status,name) {
  // AJAX Request here
  var data = {"action":'nameRecord', "goodName":status,"name":name};
  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data
  });

}

</script>

</body>

</html>
