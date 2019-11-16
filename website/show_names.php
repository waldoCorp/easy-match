<?php
require './login_script.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>

<style>
<!-- Extra CSS to make buttons BIG -->
.big-btns {
  height:200px;
  width:200px;
}
</style>

<title>Pick Names!</title>

</head>

<?php

// UUID -- should be pulled depending on who is logged in:
$uuid = $_SESSION['uuid'];
//$uuid = 'test2';

// Region to set up PHP stuff
require_once $function_path . 'get_names.php';
require_once $function_path . 'get_preferences.php';

$names = get_names($uuid,25);

$prefs = get_preferences($uuid);

// Find if we have non-standard preferences in place:
$standard_prefs = false;

if( empty($prefs['gender']) &&
  is_null($prefs['first_letter']) &&
  is_null($prefs['last_letter']) &&
  is_null($prefs['popularity']) ) {
    $standard_prefs = true;
}

// Prepare for passing to JS
$names = json_encode($names);

// Get list of letters:
$letters = range('A','Z');
?>

<body>

<?php include("header.php"); ?>

<br>

<div class="container">
  <h2 class="align-center">Approve/Disapprove Names</h2>

  <div class="row d-flex">
    <!-- No button -->
    <div class="col-2 align-items-center d-flex">
      <!--<button type="button" class="select_btn" id="noName">&#10060</button>-->
      <button type="button" class="select_btn btn btn-danger btn-lg w-100 h-100" id="noName">No</button>
    </div>

    <!-- Name -->
    <div class="col-6 display-3 text-center align-center" id="nameText">

    </div>

    <!-- Yes button -->
    <div class="col-2 align-items-center d-flex">
      <!--<button type="button" class="select_btn" id="yesName">&#9989</button>-->
      <button type="button" class="select_btn btn btn-success btn-lg w-100 h-100" id="yesName">Yes</button>
    </div>

</div>

<br>
<br>

<div class="container">
  <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#filterDiv" aria-expanded="false" aria-controls="filterDiv">
    Show Filters
  </button>
  <div class="<?php echo ($standard_prefs ? "collapse" : "collapse show"); ?>" id="filterDiv">
    <div class="row d-flex">

      <!-- gender filter -->
      <div class="col-sm">
        <h2> Gender? </h2>
        <div class="form-check" name="gender">
          <input class="form-check-input" type="checkbox" value="" id="noPref"
          <?php echo (empty($prefs['gender']) ? 'checked' : ''); ?> >
          <label class="form-check-label" for="noPref">No Preference</label>
        </div>

        <div class="form-check" name="gender">
          <input class="form-check-input" type="checkbox" value="boy" id="boy"
          <?php echo (in_array('boy', $prefs['gender']) ? 'checked' : ''); ?> >
          <label class="form-check-label" for="boy">Boys</label>
        </div>

        <div class="form-check" name="gender">
          <input class="form-check-input" type="checkbox" value="girl" id="girl"
          <?php echo (in_array('girl', $prefs['gender']) ? 'checked' : ''); ?> >
          <label class="form-check-label" for="girl">Girls</label>
        </div>

        <div class="form-check" name="gender">
          <input class="form-check-input" type="checkbox" value="neutral20" id="neutral20"
          <?php echo (in_array('neutral20', $prefs['gender']) ? 'checked' : ''); ?> >
          <label class="form-check-label" for="neutral20">Gender Neutral 20-80</label>
        </div>

        <div class="form-check" name="gender">
          <input class="form-check-input" type="checkbox" value="neutral40" id="neutral40"
          <?php echo (in_array('neutral40', $prefs['gender']) ? 'checked' : ''); ?> >
          <label class="form-check-label" for="neutral40">Gender Neutral 40-60</label>
        </div>

      </div>

    <!-- First letter filter -->
    <div class="col-sm">
      <h2> Starting Letter? </h2>
      <select id="start_select">
        <option value="">No Preference</option>
        <?php foreach( $letters as $letter ) {
	// if this letter matches their previous selection, select it:
	  if( $letter == $prefs['first_letter'] ) { ?>
	    <option value="<?php echo $letter ?>" selected="selected"><?php echo $letter ?></option>
	  <?php } else { ?>
	    <option value="<?php echo $letter ?>"><?php echo $letter ?></option>
	  <?php }
	} ?>
      </select>
    </div>

    <!-- Last Letter filter -->
    <div class="col-sm">
      <h2> Ending Letter? </h2>
      <select id="stop_select">
	<option value="">No Preference</option>
        <?php foreach( $letters as $letter ) {
	// if this letter matches their previous selection, select it:
	  if( $letter == $prefs['last_letter'] ) { ?>
	    <option value="<?php echo $letter ?>" selected="selected"><?php echo $letter ?></option>
	  <?php } else { ?>
	    <option value="<?php echo $letter ?>"><?php echo $letter ?></option>
	  <?php }
	} ?>
      </select>
    </div>

    <!-- Popularity filter -->
    <div class="col-sm">
      <h2> Popular or Unusual? </h2>
      <select id="popularity">
        <option value="" <?php echo (is_null($prefs['popularity']) ? 'selected' : ''); ?>>No Preference</option>
        <option value="popular" <?php echo ($prefs['popularity'] == 'popular' ? 'selected' : ''); ?>>Popular Names Only</option>
        <option value="unusual" <?php echo ($prefs['popularity'] == 'unusual' ? 'selected' : ''); ?>>Unusual Names Only</option>
      </select>
    </div>
  </div>
</div>
</div>

<!-- Custom JavaScript goes here -->
<script>
var nameList = <?php echo($names) ?>; // Note: Globals are bad -- maybe a better way?

$( document ).ready(function() {
  // Set name to first available name:
  $('#nameText').text(nameList[0]);
});


$('.select_btn').click(function() {
  console.log(nameList.length);
  // Get the name we're working on:
  var name = $('#nameText').text().trim(); // trim() removes whitespace

  // Then determine if we had 'yes' or 'no':
  if( $(this).attr('id') == 'noName' ) {
    // We don't like this name
    nameRecord('no',name);
  } else {
    // We do!
    nameRecord('yes',name);
  }

  // Finally, update with a new name:
  $('#nameText').text(nameList[0]);
  nameList.shift(); // Remove element we just used

  if( nameList.length < 5) {
    // Get more names!
    getNames();

    // deduplicate name list
    nameList = uniq(nameList);

  }
});


$('#filterDiv').find('input, select').change(function() {
  // One of our filters changed, so send an update:

  // Figure out if we need to modify extra checkboxes:
  if( $(this).attr('class') == 'form-check-input' ) {
    // If it was the 'No Preference' Box, uncheck other choices:
    if( $(this).attr('id') == 'noPref' && $('#noPref').is(':checked') ) {
      $('#boy').prop('checked',false);
      $('#girl').prop('checked',false);
      $('#neutral20').prop('checked',false);
      $('#neutral40').prop('checked',false);
    } else if( $('#boy').is(':checked') || $('#girl').is(':checked') ||
               $('#neutral20').is(':checked') || $('neutral40').is(':checked') ) {

      $('#noPref').prop('checked', false);
    } else {
      $('#noPref').prop('checked', true);
    }
  }

  // First do gender checkboxes:
  var gender = [];
  $('.form-check-input:checked').each(function() {
    gender.push($(this).val());
  });

  // Then all the easy ones:
  var first_letter = $('#start_select').val();
  var last_letter = $('#stop_select').val();
  var popular = $('#popularity').val();

  // Now send the update request:
  prefRecord(gender,first_letter,last_letter,popular);

});


// Function to record if we liked or disliked the name
function nameRecord(status,oldName) {
  var data = {"action":'nameRecord', "goodName":status,"name":oldName};
  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data,

   error: function(xhr, ajaxOptions, thrownError) {
       // Do something here if error
   }
  });
}

function getNames() {
  var data = {"action":'getNames',"email":"<?php echo htmlspecialchars($email) ?>"};

  // AJAX Request here
  // Maybe pass current list of names too? So we don't get duplicates?
  $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    dataType: "json",
    data: data,
    success: function(data) {
      $.merge(nameList, data); // There might be a better way to do this...
    }
  });
}

function uniq(a) {
 var seen = {};
 return a.filter(function(item) {
   return seen.hasOwnProperty(item) ? false : (seen[item] = true);
 });
}


// Function to record preferences:
function prefRecord(gender,first_letter,last_letter,popularity) {

  var data = {"action":'preferencesRecord', "gender":gender,
             "first_letter":first_letter, "last_letter":last_letter,
             "popularity":popularity};

  // AJAX Request here
  $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data,

    success: function() {
      // This is failing as getNames() takes too long and because
      // it's asynchronous (which is good in the normal use case)
      // it's not done before we try to reset the text and whatnot...
      console.log('In success!');
      console.log(nameList);
      nameList = []; // Drop old names since they might not fit new filter
      getNames(); // Refresh names list with filters
      console.log(nameList);

      $('#nameText').text(nameList[0]); // Update text with new name

    },

    error: function(xhr, ajaxOptions, thrownError) {
       // Do something here if error
    }
  });
}



</script>

</body>

</html>
