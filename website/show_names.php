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
.tooltip.show {
  opacity: 1 !important;
  filter: alpha(opacity=100);
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
  empty($prefs['first_letter']) &&
  empty($prefs['last_letter']) &&
  empty($prefs['popularity']) ) {
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
    <div class="col-6 display-3 text-center align-center" id="nameText"
     data-toggle="tooltip" data-placement="bottom" title="We've run out of names to show with the current filters in place.">

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
      <h2>Gender Preference</h2>

      <select id="gender" data-toggle="tooltip" data-placement="right"
        title="<img src='images/reduced-name-venn.png' />">
        <option value="" <?php echo (is_null($prefs['gender']) ? 'selected' : ''); ?>>No Preference</option>
        <option value="boy" <?php echo ($prefs['gender'] == 'boy' ? 'selected' : ''); ?>>Boys</option>
        <option value="girl" <?php echo ($prefs['gender'] == 'girl' ? 'selected' : ''); ?>>Girls</option>
        <option value="neutral20" <?php echo ($prefs['gender'] == 'neutral20' ? 'selected' : ''); ?>>Gender Neutral</option>
      </select>
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
  nameTextUpdate(nameList);

  // Turn on tooltips for filters:
  $('#gender').tooltip({
    container: 'body',
    html: true
  });

});

$('.select_btn').click(function() {

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

  nameList.shift(); // Remove element we just used

  // Finally, update with a new name:
  nameTextUpdate(nameList);

  if( nameList.length < 5) {
    // Get more names using a promise
    getNames().then( function(respData) {
      $.merge(nameList, respData); // There might be a better way to do this...
      // deduplicate name list
      nameList = uniq(nameList);
    });


  }
});


$('#filterDiv').find('select').change(function() {
  // Close tooltip (otherwise it stays until next click on page):
  $(this).tooltip('hide');

  // Get Filter Results:
  var gender = $('#gender').val();
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
  // AJAX Request here
  // Maybe pass current list of names too? So we don't get duplicates?
 return $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    dataType: "json",
    data: {"action":'getNames',"email":"<?php echo htmlspecialchars($email) ?>"}
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

  var prefData = {"action":'preferencesRecord', "gender":gender,
             "first_letter":first_letter, "last_letter":last_letter,
             "popularity":popularity};

  // AJAX Request to update preferences
  var prefUpdate = $.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: prefData,
    error: function(xhr, ajaxOptions, thrownError) {
       // Do something here if error
    }
  });

  // Use promise to only do the name update after the preferences are ready...
  $.when(prefUpdate).then( // After the preferences have been updated
    getNames().then( function(respData) { // Get more names
      nameList = respData;
      nameTextUpdate(nameList);
    })
  )
}

// Function to update #nameText and display an error if we're out of names:
function nameTextUpdate(name) {
  if( name ) {
    $('#nameText').text(name[0]);
    // Turn on buttons if they had been turned off:
    $('.select_btn').attr('disabled', false);
    $('#nameText').tooltip('hide');
    $('#nameText').tooltip('disable');
  } else {
    // We're out of names D:
    $('#nameText').text('N/A');

    // Disable buttons:
    $('.select_btn').attr('disabled', true);

    // Maybe add an alert or something?
    // Turn on tooltip indicating no more names
    $('#nameText').tooltip('enable');
    $('#nameText').tooltip('show');
  }

}

</script>

</body>

</html>
