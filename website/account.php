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


$uuid = $_SESSION['uuid'];

$partners = get_partners($uuid);
$rejected_partners = get_rejected_partners($uuid);
$invitations = get_invitations($uuid);
$uname = get_username($uuid);

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
    <fieldset>
      <p>See and/or download all the data we have about the names you have seen:</p>
      <p>
        <a class="btn btn-primary" href="download_data.php"> My Data</a>
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

</script>

</body>

</html>
