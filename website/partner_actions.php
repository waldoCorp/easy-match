<?php
require './login_script.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Add/Select a Partner!</title>
</head>

<?php
// Region to set up PHP stuff
//require_once '/srv/nameServer/functions.php/get_partners.php';
//require_once '/srv/nameServer/functions.php/get_partner_invitations.php';


$uuid = $_SESSION['uuid'];

//$partners = get_partners($uuid);
$partners = array('Bob','Alice','Sue');
//$invitations = get_partner_invitations($uuid);
$invitations = array('Charlie','David','Erin');

?>

<body>

<?php include("header.php"); ?>


<div class="container">
 <h2>Add a Partner</h2>

 <form>
    <fieldset>
      <legend>Invite a friend to compare matches!</legend>
      <p>
        <label for="email">Email</label>
        <input id="friend_email" type="email" placeholder="Enter Email Address of a Partner">
      </p>

      <p>
        <button type="button" id="add_friend" value="Submit">Add Friend!</button>
      </p>

    </fieldset>
  </form>
</div>

<br>

<div class="container">
  <h2>Choose which partner to match names with</h2>

  <select id="partner_select">
    <option value="">Pick a Partner</option>
    <?php foreach($partners as $partner) { ?>
      <option value="<?php echo htmlspecialchars($partner); ?>">
        <?php echo htmlspecialchars($partner); ?>
      </option>
    <?php } ?>
  </select>
</div>

<br>
<div class="container">

  <h2>Invitations from Other Users</h2>
<?php foreach($invitations as $invitation) { ?>
  <div class="row py-2 border-bottom">
    <div class="col-sm align-items-center d-flex" name="name">
      <?php echo(htmlspecialchars($invitation)); ?>
    </div>
    <div class="col-sm align-items-center d-flex">
      <button type="button" class="select_btn btn reject_btn btn-danger">Reject</button>
    </div>
    <div class="col-sm align-items-center d-flex">
      <button type="button" class="select_btn btn accept_btn btn-success">Accept</button>
    </div>
  </div>
<?php } ?>

</div>


<!-- Custom JavaScript goes here -->
<script>
$('#add_friend').click(function() {
  var new_email = $(this).val();
  inviteFriend(new_email);
});

$('#partner_select').change(function() {
  var partner_email = $(this).val()
  partnerSelect(partner_email);
});


$('.select_btn').click(function() {
  // Find the partner's email:
  var name_field = $(this).closest("div.row").find("[name='name']");
  var partner_text = name_field.text().trim();

  // Find if accept/reject status:
  if( $(this).hasClass('accept_btn') ) {
    // Accepted invitation:
    invitationResponse(partner_text,'accept');
  } else {
    invitationResponse(partner_text,'reject');
  }

  // Remove line either way:
  $(this).closest("div.row").fadeOut(300, function(){$(this).remove();});

});


function inviteFriend(email) {
  // AJAX Request here
  var data = {"action":'inviteFriend', "new_email":email};

  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data
  });

}

function partnerSelect(email) {
  // AJAX Request here
  var data = {"action":'partnerSelect', "partner_email":email};

  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data
  });

}


function invitationResponse(email,status) {
  // AJAX Request here
  var data = {"action":'partnerResponse', "partner_email":email,"status":status};

  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data
  });

  console.log('Clicked!');
}

</script>

</body>

</html>
