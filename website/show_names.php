<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Pick Names!</title>
</head>

<?php
// Region to set up PHP stuff

//$names = get_name_list();
$names = array('Alice','Bob','Charlie');

// Prepare for passing to JS
$names = json_encode($names);

?>

<body>

<?php include("header.php"); ?>


<div class="container">
  <h2>Upvote/Downvote Names</h2>

  <div class="row">
    <!-- No button -->
    <div class="col-sm">
      <!--<button type="button" class="select_btn" id="noName">&#10060</button>-->
      <button type="button" class="select_btn btn btn-danger btn-large" id="noName">No</button>
    </div>

    <!-- Name -->
    <div class="col-sm" id="nameText">

    </div>

    <!-- Yes button -->
    <div class="col-sm">
      <!--<button type="button" class="select_btn" id="yesName">&#9989</button>-->
      <button type="button" class="select_btn btn btn-success btn-large" id="yesName">Yes</button>
    </div>

</div>


<!-- Custom JavaScript goes here -->
<script>
var nameList = <?php echo($names) ?>;

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
    //nameList.push(getNames()) // Add new names to end of array
    //nameList.concat(getNames()) // If we return an array, use this
  }
});

// Function to record if we liked or disliked the name
function nameRecord(status,oldName) {
  var data = {action:'nameRecord', goodName:status,name:oldName};
  // AJAX Request here
/*$.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data
  });*/
}

function getNames() {
  var data = {action:'getNames'};
  // AJAX Request here
/*$.ajax({
    type: "POST",
    url: "./endpoints/ajax_endpoint.php",
    data: data,
    success: function() {
      return result
    }
  });*/
}
</script>

</body>

</html>
