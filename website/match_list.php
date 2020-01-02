<?php
require './login_script.php';
?>


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
require_once $function_path . 'get_matching_names_list.php';
require_once $function_path . 'get_uuid.php';
require_once $function_path . 'get_partners.php';
require_once $function_path . 'get_username.php';

$uuid = $_SESSION['uuid'];

// Find existing partners:
$partners = get_partners($uuid);

$partner_email = $_SESSION['partner_email'];
$partner_uuid = get_uuid($partner_email);

$partner_name = get_username($partner_uuid);
// FOR TESTING
//$partner_uuid = 'test1';
//$partner_email = 'test1';


$names = get_matching_names_list($uuid,$partner_uuid);

//$names = array('Alice','Bob','Charlie');

// shuffle($names); // Randomizing seems like as good a call as any here
sort($names); // sort ensures new matches (with stars) are at the top

?>

<body>

<?php include("header.php"); ?>
<main role="main">
<div class="container">
  <h2>Choose which partner to match names with</h2>

 <div class="form-group w-25">
  <select id="partner_select" class="form-control">
    <option value="">Pick a Partner</option>
    <?php foreach($partners as $partner) { ?>
      <option value="<?php echo htmlspecialchars($partner['email']); ?>"
        <?php echo ($partner['email'] == $partner_email) ? 'selected' : ''; ?>>
	<?php if( !empty($partner['uname']) ) {
                echo htmlspecialchars($partner['uname']) ." (". htmlspecialchars($partner['email']) .")";
              } else {
                echo htmlspecialchars($partner['email']);
              }?>
      </option>
    <?php } ?>
  </select>
 </div>
</div>

<br>




<div class="container">
  <?php if( empty($partner_uuid) ) { ?>
    <h2>No partner selected to match with</h2>
  <?php } else { ?>
    <h2>List of names you and <?php echo htmlspecialchars($partner_name) . " (". htmlspecialchars($partner_email).")"; ?> agree on</h2>

    <?php if( !is_null($names) ) {
      foreach($names as $name) { ?>
      <div class="row">
        <div class="col-sm">
          <?php echo(htmlspecialchars($name)); ?>
        </div>
      </div>
      <?php } ?>
    <?php } else { ?>
      <div class="row">
        <div class="col-sm">
          No Matching Names!
        </div>
      </div>
    <?php } ?>
  <?php } ?>
</div>
</main>

<?php include("footer.php"); ?>

<script>
$('#partner_select').change(function() {
  var partner_email = $(this).val()
  partnerSelect(partner_email);
});

function partnerSelect(email) {
  // AJAX Request here
  var data = {"action":'partnerSelect', "partner_email":email};

  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data,
    success: function(data) {
      location.reload()
    }
  });

}


</script>


</body>

</html>
