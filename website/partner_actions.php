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

<main role="main">
<div class="container">
 <h2>Add a Partner</h2>

 <form>
      <legend>Invite a friend to compare matches!</legend>
      <div class="form-group w-25">
        <input id="friend_email" class="form-control" type="email" placeholder="Enter Email Address of a Partner">
      </div>

      <button type="button" id="add_friend" class="btn btn-primary" value="Submit">Add Friend!</button>

 </form>

 <br>

 <div id="emailAlert" class="alert alert-success alert-dismissible fade show" role="alert" style='display:none;'>
 </div>

</div>


<br>



<?php if( !empty($invitations) ) { ?>
<div class="container">

  <h2>Invitations from Other Users</h2>
<?php foreach($invitations as $uuid=>$invitation) { ?>
  <div class="row py-2 border-bottom">

    <?php if( !empty($invitation["uname"]) ) { ?>
    <div class="col-sm align-items-center d-flex" name="name">
      <?php echo(htmlspecialchars($invitation['uname'])); ?>
    </div>
    <?php } ?>

    <div class="col-sm align-items-center d-flex" name="name">
      <?php echo(htmlspecialchars($invitation['email'])); ?>
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

      <?php if( !empty($part["uname"]) ) { ?>
      <div class="col-sm align-items-center d-flex" name="name">
        <?php echo(htmlspecialchars($part['uname'])); ?>
      </div>
      <?php } ?>

      <div class="col-sm align-items-center d-flex" name="name">
        <?php echo(htmlspecialchars($part['email'])); ?>
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

      <?php if( !empty($r_part["uname"]) ) { ?>
      <div class="col-sm align-items-center d-flex" name="name">
        <?php echo(htmlspecialchars($r_part['uname'])); ?>
      </div>
      <?php } ?>

      <div class="col-sm align-items-center d-flex" name="name">
        <?php echo(htmlspecialchars($r_part['email'])); ?>
      </div>
      <div class="col-sm align-items-center d-flex">
        <button type="button" class="select_btn btn accept_btn btn-success" value="<?php echo(htmlspecialchars($uuid)); ?>">Accept</button>
      </div>
    </div>
<?php } ?>

  </div>
</div>
<?php } ?>

</main>

<?php include("footer.php"); ?>

<!-- Custom JavaScript goes here -->
<script>
$('#add_friend').click(function() {
  var new_email = $('#friend_email').val();
  inviteFriend(new_email);
  // Reset to stop spamming the button
  $('#friend_email').val('');

  emailAlert(new_email);

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

function emailAlert(email) {
  const alert = $('#emailAlert');
  alert.finish();
  alert.html('Invitation sent to '+email+'!');
  alert.show();

  window.setTimeout(function() {
    alert.fadeTo(1000,0).slideUp(1000, function() {
      $(this).hide();
      $(this).css('opacity',100);
    });
  }, 3000);

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
