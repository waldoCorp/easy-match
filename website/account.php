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
require_once $function_path . 'get_username.php';
require_once $function_path . 'get_comm_prefs.php';
require_once $function_path . 'get_data_pref.php';


$uuid = $_SESSION['uuid'];

$partners = get_partners($uuid);
$rejected_partners = get_rejected_partners($uuid);
$invitations = get_invitations($uuid);
$uname = get_username($uuid);
$comm = get_comm_prefs($uuid);
$data_opt_out = get_data_pref($uuid);

?>

<body>

<?php include("header.php"); ?>


<main role="main">
<div class="container">
 <h2>Account Info</h2>
 <form>
      <legend>Username</legend>
      <div class="form-group w-25">
        <input id="username" class="form-control" type="text" placeholder="No Username Yet"
         aria-describedby="unameHelp" value="<?php echo htmlspecialchars($uname) ?>">
        <small id="unameHelp" class="form-text text-muted">Your username is only used to identify yourself to other users and is optional</small>
      </div>
  </form>
</div>

<br>


<div class="container">
 <h2>Get Collected Data</h2>
 <form>
    <p>See and/or download all the data we have about the names you have seen:</p>

    <a class="btn btn-primary" href="download_data.php"> My Data</a>

    <br><br>

    <div class="form-check">
      <input type="checkbox" id="dataOptOut" class="form-check-input" value=""
      <?php echo (!$data_opt_out ? 'checked' : ''); ?>
      >
      <label class="form-check-label" for="dataOptOut">
        WaldoCorp can share my anonymized data with third parties.
      </label>
    </div>
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
 <h2>Communication Preferences</h2>
 <form id="commForm">
    <p>Choose what types of communication(s) you would like to receive from us:</p>

    <div class="form-check">
      <input type="checkbox" id="allComm" class="form-check-input commCheck" value="allComm"
      <?php echo ($comm['all_comm'] ? 'checked' : ''); ?>
      >
      <label class="form-check-label" for="allComm">
        Any and all communications Waldo Corp. puts out about Name Selector.
      </label>
    </div>

    <div class="form-check">
      <input type="checkbox" id="partnersComm" class="form-check-input commCheck" value="partnerComm"
      <?php echo ($comm['functional'] ? 'checked' : ''); ?>
      >
      <label class="form-check-label" for="partnersComm">
        Only emails relating to site functionality (when someone requests you as a partner, you have new matches,...).
      </label>
    </div>

    <div class="form-check">
      <input type="checkbox" id="promotionComm" class="form-check-input commCheck" value="promotionComm"
      <?php echo ($comm['promotion'] ? 'checked' : ''); ?>
      >
      <label class="form-check-label" for="promotionComm">
        Promotional news from Waldo Corp. such as new services or results of any research done using data from this site.
      </label>
    </div>

    <div class="form-check">
      <input type="checkbox" id="noComm" class="form-check-input commCheck" value="noComm"
      <?php echo ($comm['none'] ? 'checked' : ''); ?>
      >
      <label class="form-check-label" for="noComm">
        No communications of any kind (password resets will still work), this means if someone tries to match names with you, they'll need to reach out to you not through this site.
      </label>
    </div>

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

$('#username').change(function() {
  const uname = $(this).val();
  unameUpdate(uname);
});

function unameUpdate(uname) {
  // AJAX Request here
  var data = {"action":'unameUpdate', "uname":uname};

  // AJAX Request here
  $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data,
  });
}

$('input.commCheck').change(function() {
  const val = $(this).val();
  const checked = $(this).prop('checked');
  if( checked ) {
    if( val === 'allComm' ) {
      $('#partnersComm').prop('checked', false);
      $('#promotionComm').prop('checked', false);
      $('#noComm').prop('checked', false);
    } else if ( val === 'noComm' ) {
      $('#partnersComm').prop('checked', false);
      $('#promotionComm').prop('checked', false);
      $('#allComm').prop('checked', false);
    } else {
      $('#allComm').prop('checked', false);
      $('#noComm').prop('checked', false);
    }
  } else {
    if( $('#commForm input:checkbox:checked').length < 1) {
      $('#noComm').prop('checked', true);
    }
  }

  var commPrefs = [];
  $('input.commCheck:checked').each(function() {
    commPrefs.push($(this).val());
  });

  commUpdate(commPrefs);

});


function commUpdate(commPref) {
  // AJAX Request here
  var data = {"action":'communicationsUpdate', "commPref":commPref};

  // AJAX Request here
  $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data,
  });
}

// Update data opt-out status:
$('#dataOptOut').change(function() {
  const checked = $(this).prop('checked');
  dataUpdate(!checked);
});

function dataUpdate(dataOptOut) {
  // AJAX Request here
  var data = {"action":'dataUpdate', "dataOptOut":dataOptOut};

  // AJAX Request here
  $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data,
  });
}



</script>

</body>

</html>
