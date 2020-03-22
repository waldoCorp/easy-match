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

//unset($_SESSION['partner_email']);


$names = get_matching_names_list($uuid,$partner_uuid);

// shuffle($names); // Randomizing seems like as good a call as any here
sort($names); // sort ensures new matches (with stars) are at the top

?>

<body>

<?php include("header.php"); ?>
<main role="main">
<div class="container">
 <div class="row align-items-center">
  <div class="col-md-8">
   <h2>Choose which partner to match names with</h2>

   <div class="form-group w-50">
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

  <div class="col">
   <form>
    <div class="form-group">
           <a class="btn btn-primary btn-sm" href="my_names.php" id="myNames"
             aria-describedby="myNamesText">My Names</a>
          <small id="myNamesText" class="form-text text-muted">See names you have rated</small>

    </div>
   </form>
  </div>
 </div>
</div>

<br>




<div class="container">
  <?php if( empty($partner_uuid) ) { ?>
    <h2>No partner selected to match with</h2>
  <?php } else { ?>
    <h2>List of names you and <?php echo htmlspecialchars($partner_name) . " (". htmlspecialchars($partner_email).")"; ?> agree on</h2>
  <div class="row">
    <div class="col">
    <?php if( !is_null($names) ) {
      foreach($names as $name) { ?>
      <div class="row">
        <div class="col-sm">
          <?php echo(htmlspecialchars($name)); ?>
        </div>
      </div>


      <?php } ?>
    </div>
      <div class="col">
        <br>
         <div class="dropdown">
             <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="downloadDropdown"
                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                     aria-describedby="downloadText">Export List</button>
            <div class="dropdown-menu" aria-labelledby="downloadDropdown">
              <a class="dropdown-item" href="download_list.php">Download</a>
              <a class="dropdown-item"
                href="mailto:?subject=Easy%20Match%20Names%20List&body=Here%20is%20the%20list%20of%20names%20you%20and%20<?php
                  echo (!is_null($partner_name) ? $partner_name."%20(".htmlspecialchars($partner_email).")%0D%0A" : htmlspecialchars($partner_email)."%0D%0A" );
                  ?>agree%20on:%0D%0A%0D%0A<?php
                  foreach($names as $name) {
                    echo(htmlspecialchars($name)."%0D%0A");
                  }?>%0D%0AThanks!%0D%0ACatbot%20and%20the%20Easy%20Match%20Team"
              >Email</a>
            </div>
            <small id="downloadText" class="form-text text-muted">Download or email list of matching names</small>


          </div>
      </div>

    <?php } else { ?>
      <div class="row">
        <div class="col-sm">
          No Matching Names!
        </div>
      </div>
    </div>
    <?php } ?>
  <?php } ?>

  </div>
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
