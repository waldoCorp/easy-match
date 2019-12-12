<?php
require './login_script.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Account Management</title>
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

<main role="main">
<div class="container">
 <h2>Account Info</h2>

 <form>
    <fieldset>
      <legend>Username</legend>
      <p>
        <input id="username" type="text" placeholder="" data-toggle="tooltip"
         data-placement="top" title="Your username is only used to identify yourself to other users">
      </p>
    </fieldset>
  </form>
</div>

<br>

<div class="container">
 <h2>Get Collected Data</h2>
 <form>
    <fieldset>
      <p>See and/or download all the data we have about the names you have seen:</p>
      <p>
        <a class="btn btn-secondary" href="user_data.php">Download</a>
      </p>
    </fieldset>
  </form>
</div>

<br>

<div class="container">
 <h2>View Selected Names</h2>
 <form>
    <fieldset>
      <p>See names you've liked/disliked and (potentially) change your mind:</p>
      <p>
          <a class="btn btn-primary" href="my_names.php">My Names</a>
      </p>
    </fieldset>
  </form>
</div>

<br>

<div class="container">
 <h2>Delete Account</h2>
 <form>
    <fieldset>
      <p>Permanently delete your account and all of our stored data about it:</p>
      <p>
        <button type="button" class="btn btn-danger" id="delete_account"
          data-toggle="modal" data-target="#deletionModal" value="delete">Delete</button>
      </p>
    </fieldset>
  </form>
</div>


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

<!-- Modal for account deletion (to be sure...) -->
<div class="modal fade" id="deletionModal" tabindex="-1" role="dialog" aria-labelledby="deleteionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletionModalLabel">Are You Sure?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This will permanently delete your account and all data associated with it.
        This cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Not Now</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" id="realDelete">Really Delete</button>
      </div>
    </div>
  </div>
</div>


</main>

<?php include("footer.php"); ?>

<!-- Custom JavaScript goes here -->
<script>
// Enable tooltips:
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$('#realDelete').click(function() {
  console.log("Clicked!");
  // AJAX Request here
  var data = {"action":'deleteAccount'};

  $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data,
    success: function() {
      // Go back to index since we've logged out now (permanently)
      window.location.href = "./index.php";
    }
  });
});

$('#add_friend').click(function() {
  var new_email = $('#friend_email').val();
  inviteFriend(new_email);
  // Reset to stop spamming the button
  $('#friend_email').val('');

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
