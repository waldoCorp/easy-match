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
require_once $function_path . 'get_partners.php';
require_once $function_path . 'get_rejected_partners.php';
require_once $function_path . 'get_invitations.php';


$uuid = $_SESSION['uuid'];

$partners = get_partners($uuid);
$rejected_partners = get_rejected_partners($uuid);
$invitations = get_invitations($uuid);

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

<?php if( !empty($partners) ) { ?>
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
<?php } ?>

<br>

<?php if( !empty($invitations) ) { ?>
<div class="container">

  <h2>Invitations from Other Users</h2>
<?php foreach($invitations as $uuid=>$invitation) { ?>
  <div class="row py-2 border-bottom">
    <div class="col-sm align-items-center d-flex" name="name">
      <?php echo(htmlspecialchars($invitation)); ?>
    </div>
    <div class="col-sm align-items-center d-flex">
      <button type="button" class="select_btn btn reject_btn btn-danger" value="<?php echo(htmlspecialchars($uuid)); ?>">Reject</button>
    </div>
    <div class="col-sm align-items-center d-flex">
      <button type="button" class="select_btn btn accept_btn btn-success" value="<?php echo(htmlspecialchars($uuid)); ?>">Accept</button>
    </div>
  </div>
<?php } ?>

</div>

<?php } ?>
<br>


<?php if( !empty($partners) ) { ?>
<div class="container">
  <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#rejectPartners" aria-expanded="false" aria-controls="rejectedInvites">
  Remove paired partner
  </button>

  <div class="collapse" id="rejectPartners">
    <br>
    <h4>Un-pair with a person</h4>
<?php foreach($partners as $uuid=>$part) { ?>
    <div class="row py-2 border-bottom">
      <div class="col-sm align-items-center d-flex" name="name">
        <?php echo(htmlspecialchars($part)); ?>
      </div>
      <div class="col-sm align-items-center d-flex">
        <button type="button" class="select_btn btn reject_btn btn-danger" value="<?php echo(htmlspecialchars($uuid)); ?>">Reject</button>
      </div>
    </div>
<?php } ?>

  </div>
</div>
<?php } ?>

<?php if( !empty($rejected_partners) ) { ?>
<div class="container">
  <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#rejectedInvites" aria-expanded="false" aria-controls="rejectedInvites">
  Show rejected offers
  </button>

  <div class="collapse" id="rejectedInvites">
    <br>
    <h4>Rejected Invitations</h4>
<?php foreach($rejected_partners as $uuid=>$r_part) { ?>
    <div class="row py-2 border-bottom">
      <div class="col-sm align-items-center d-flex" name="name">
        <?php echo(htmlspecialchars($r_part)); ?>
      </div>
      <div class="col-sm align-items-center d-flex">
        <button type="button" class="select_btn btn accept_btn btn-success" value="<?php echo(htmlspecialchars($uuid)); ?>">Accept</button>
      </div>
    </div>
<?php } ?>

  </div>
</div>
<?php } ?>

<!-- Custom JavaScript goes here -->
<script>
$('#add_friend').click(function() {
  var new_email = $('#friend_email').val();
  inviteFriend(new_email);
  // Reset to stop spamming the button
  $('#friend_email').val('');

});

$('#partner_select').change(function() {
  var partner_email = $(this).val()
  partnerSelect(partner_email);
});


$('.select_btn').click(function() {
  // Find the partner's uuid:
  var uuid = $(this).val();
  var name_field = $(this).closest("div.row").find("[name='name']");
  var partner_text = name_field.text().trim();

  // Find if accept/reject status:
  if( $(this).hasClass('accept_btn') ) {
    // Accepted invitation:
    invitationResponse(uuid,'accept');
  } else {
    invitationResponse(uuid,'reject');
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


function invitationResponse(uuid,status) {
  // AJAX Request here
  var data = {"action":'partnerResponse', "partner_uuid":uuid,"status":status};

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
