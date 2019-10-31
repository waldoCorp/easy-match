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
require_once '/srv/nameServer/functions.php/get_names.php';

$names = get_names($uuid,25);
//$names = array('Alice','Bob','Charlie');

// Prepare for passing to JS
$names = json_encode($names);


?>

<body>

<?php include("header.php"); ?>

<br>
<div class="container">
  <h2 class="align-center">Upvote/Downvote Names</h2>
  <br>
  <div class="row d-flex">
    <!-- gender filter -->
    <div class="container">
      <h2> Gender? </h2>
      <select id="gender_select">
        <option value="">No Preference</option>
        <option value="">Boys</option>
        <option value="">Girls</option>
        <option value="">Gender Neutral 20-80</option>
        <option value="">Gender Neutral 40-60</option>
      </select>
    </div>
    <!-- First letter filter --> 
    <div class="container">
      <h2> Starting Letter? </h2>
      <select id="start_select">
        <option value="">No Preference</option>
	<option value="">A</option>
	<option value="">Z</option>
	//figure out a loop for A:Z
      </select>
    </div>
    <!-- Last Letter filter -->
    <div class="container">
      <h2> Ending Letter? </h2>
      <select id="stop_select">
        <option value="">No Preference</option>
	<option value="">A</option>
	<option value="">Z</option>
	//figure out a loop for A:Z
      </select>
    </div>
    <!-- Popularity filter -->
    <div class="container">
      <h2> Popular or Unusual? </h2>
      <select id="popularity_select">
        <option value="">No Preference</option>
	<option value="">Popular Names Now</option>
	<option value="">Unusal Names Now</option>
      </select>
    </div>
</div>
<br>
<div class="row d-flex">
    <!-- No button -->
    <div class="col-2 align-items-center d-flex">
      <!--<button type="button" class="select_btn" id="noName">&#10060</button>-->
      <button type="button" class="select_btn btn btn-danger btn-lg w-100 h-100" id="noName">No</button>
    </div>

    <!-- Name -->
    <div class="col-5 display-3 text-center align-center" id="nameText">

    </div>

    <!-- Yes button -->
    <div class="col-2 align-items-center d-flex">
      <!--<button type="button" class="select_btn" id="yesName">&#9989</button>-->
      <button type="button" class="select_btn btn btn-success btn-lg w-100 h-100" id="yesName">Yes</button>
    </div>

</div>


<!-- Custom JavaScript goes here -->
<script>
var nameList = <?php echo($names) ?>; // Note: Globals are bad -- maybe a better way?

$( document ).ready(function() {
  // Set name to first available name:
  $('#nameText').text(nameList[0]);
  nameList.shift(); // Remove element we just used
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
    function uniq(a) {
        var seen = {};
        return a.filter(function(item) {
            return seen.hasOwnProperty(item) ? false : (seen[item] = true);
    });
    nameList = uniq(nameList);
}
    console.log(nameList);
  }
});

// Function to record if we liked or disliked the name
function nameRecord(status,oldName) {
  var data = {"action":'nameRecord', "goodName":status,"name":oldName};
  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data
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
</script>

</body>

</html>
