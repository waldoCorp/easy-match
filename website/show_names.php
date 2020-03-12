<?php
require './login_script.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Google Font Lookup -->
<link href="https://fonts.googleapis.com/css?family=Cousine&display=swap" rel="stylesheet">

<?php include("./resources.php"); ?>

<style>
.tooltip.show {
  opacity: 1 !important;
  filter: alpha(opacity=100);
}

.monospace {
  font-family: 'Cousine', monospace;
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

<main role="main">
<div class="container h-100">

  <h2 class="align-center" id ="oldNameText">&nbsp;</h2> <!-- maybe bad practice, but no assigned value for first name shown and doesn't appear on page -->

  <div class="row justify-content-center">
    <!-- No button -->
    <div class="col-1-auto align-items-center d-flex">
      <!--<button type="button" class="select_btn" id="noName">&#10060</button>-->
      <button type="button" class="select_btn btn btn-danger btn-lg" style="font-size: 4vw;" id="noName">No</button>
    </div>

    <!-- Name -->
    <div class="col-6 d-flex" style="height: 116px;">
      <h1 class="text-center align-self-center mx-auto monospace" id="nameText"
        data-toggle="tooltip" data-placement="bottom" title="We've run out of names to show with the current filters in place.">
      </h1>
    </div>

    <!-- Yes button -->
    <div class="col-1-auto align-items-center d-flex">
      <!--<button type="button" class="select_btn" id="yesName">&#9989</button>-->
      <button type="button" class="select_btn btn btn-success btn-lg" style="font-size: 4vw;" id="yesName">Yes</button>
    </div>

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
        <option value="boy" <?php echo ($prefs['gender'] == 'boy' ? 'selected' : ''); ?>>Traditionally Boy's</option>
        <option value="girl" <?php echo ($prefs['gender'] == 'girl' ? 'selected' : ''); ?>>Traditionally Girl's</option>
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

<br>
<br>

<div class="container">
  <form>
    <div class="form-group">
           <a class="btn btn-outline-info btn-sm" href="my_names.php" id="myNames"
             aria-describedby="myNamesText">My Names</a>
          <small id="myNamesText" class="form-text text-muted">See names you have rated</small>

    </div>
   </form>
</div>

</main>

<?php include("footer.php"); ?>

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

    // Show prior name if we hit a match!
    if( nameList[0]['match'] ){
	// If previous animation is still running, kill it
	$('#oldNameText').finish();

     // $('#oldNameText').text(nameList[0]['name']);
	$('#oldNameText').text(nameList[0]['name'] + ' is a match!');
	$('#oldNameText').animate({'opacity': 0}, 3000, function() {
	  $(this).html('&nbsp;');
	}).animate({'opacity': 1}, 1);
        showMatchIcon();
    }

  }

  // Finally, update with a new name:
  nameList.shift();
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
       // If we're in the error block either we've lost the connection
       // or our session expired, so reload the page to force re-login
       location.reload(true);
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
   return seen.hasOwnProperty(item['name']) ? false : (seen[item['name']] = true);
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
  const nameText = $('#nameText');

  if( name.length !== 0 ) {

    // Adjust size of text to accomodate name:
    const len = name[0]['name'].length;
    nameText.css("font-size", fontFunc(len) );

    nameText.text(name[0]['name']);

    // Turn on buttons if they had been turned off:
    $('.select_btn').attr('disabled', false);
    nameText.tooltip('hide');
    nameText.tooltip('disable');

  } else {
    // We're out of names D:
    nameText.text('N/A');
    nameText.removeClass("sm-text md-text tn-text");
    nameText.addClass("lg-text");

    // Disable buttons:
    $('.select_btn').attr('disabled', true);

    // Maybe add an alert or something?
    // Turn on tooltip indicating no more names
    nameText.tooltip('enable');
    nameText.tooltip('show');
  }

}

function fontFunc(length) {
  // Based on Bootstrap's breakpoints:
  const smallWinSize = 576;  // BS Extra Small
  const medWinSize = 768;    // BS Medium
  const largeWinSize = 992; // BS Large
  const extraLargeWinSize = 1200; // BS Extra Large

  const smallFontSize = 15; //vw
  const medFontSize = 17;  // vw
  const largeFontSize = 60; // pt
  const extraLargeFontSize = 70; // pt
  const width = $(window).width();

  if(length < 8) {length = 8};

  if (smallWinSize <= width && width < medWinSize) {
   // "Small" Window
   return (medFontSize/Math.log(1.5*length)) + "vw";
  } else if (width < smallWinSize) {
   // "Extra Small"
   return (smallFontSize/Math.log(length)) + "vw";
  } else if (medWinSize <= width && width < largeWinSize) {
   // "Medium"
    return (medFontSize/Math.log(1.5*length)) + "vw";
  } else if (largeWinSize <= width && width < extraLargeWinSize) {
   // "Large"
    return largeFontSize + "pt";
  } else {
   // "Extra Large"
    return extraLargeFontSize + "pt";
  }
}

function showMatchIcon() {
  if( $('#newMatchSR').text().length == 0 ) {
    feather.replace({
      stroke: "#D4AC0D",
      'style': 'float:right;margin-left:-100px;margin-top:-7px;',
    });

    $('#tooltip-matches').tooltip();
    $('#newMatchSR').text('You have new matches with a partner');

    // Finally, update server variable to show match icon always
    return $.ajax({
      type: "POST",
      url: "./endpoints/ajax_endpoint.php",
      data: {"action":'updateSessionMatch'}
    });

  }
}

</script>

</body>

</html>
