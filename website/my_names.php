<?php
require './login_script.php';
?>

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
require_once $function_path . 'get_selections.php';


$uuid = $_SESSION['uuid'];
//$uuid = 'test1';


$selections = get_selections($uuid);
//var_dump($selections);

?>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Change what you think about a name</h2>
  <a href="account.php">Back to Account</a>

<?php foreach($selections as $selection) { ?>
  <div class="row py-2 border-bottom">
    <div class="col-sm" name="name">
      <?php echo(htmlspecialchars($selection['name'])); ?>
    </div>
    <div class="col-sm" name="selected">
      <?php echo ($selection['selected'] ? 'Yes' : 'No') ; ?>
    </div>
    <div class="col-sm">
      <button type="button" class="swap_btn">
        <?php echo ($selection['selected'] ? 'Actually, No' : 'Actually, Yes') ; ?>
      </button>
    </div>
  </div>
<?php } ?>
</div>
</main>

<?php include("footer.php"); ?>

<!-- Custom JavaScript goes here -->
<script>
$('.swap_btn').click(function() {
  // Find Current status:
  var cur_field = $(this).closest("div.row").find("[name='selected']");
  var cur_text = cur_field.text().trim();

  // And the name that was swapped:
  var name_field = $(this).closest("div.row").find("[name='name']");
  var name_text = name_field.text().trim();

  // AJAX request to swap name choice in DB.

  // Show the swap on the page:
  if( cur_text == 'No' ) {
    cur_field.text('Yes');
   $(this).text('Actually, No');
    updateNameStatus('yes',name_text);
  } else {
    cur_field.text('No');
   $(this).text('Actually, Yes');
    updateNameStatus('no',name_text);
  }

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
