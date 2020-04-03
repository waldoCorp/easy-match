<?php
require './login_script.php';
?>

<!DOCTYPE html>
<!--
 -    Copyright (c) 2020 Ben Cerjan, Lief Esbenshade
 -
 -    This file is part of Easy Match.
 -
 -    Easy Match is free software: you can redistribute it and/or modify
 -    it under the terms of the GNU Affero General Public License as published by
 -    the Free Software Foundation, either version 3 of the License, or
 -    (at your option) any later version.
 -
 -    Easy Match is distributed in the hope that it will be useful,
 -    but WITHOUT ANY WARRANTY; without even the implied warranty of
 -    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 -    GNU Affero General Public License for more details.
 -
 -    You should have received a copy of the GNU Affero General Public License
 -    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.
-->
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php include("./resources.php"); ?>


<title>Edit Name Selections</title>
</head>

<?php
// Region to set up PHP stuff
require_once $function_path . 'get_selections.php';


$uuid = $_SESSION['uuid'];
//$uuid = 'test1';


$selections = get_selections($uuid);
//var_dump($selections);

?>

<body>

<?php include("header.php"); ?>

<main role="main">
<div class="container">
  <h2>Change what you think about a name</h2>
  <a href="show_names.php">Show me more names!</a>
  <table class="table" id="selectionsTable">
    <?php foreach($selections as $selection) { ?>
    <tr>
      <td name="name">
        <?php echo(htmlspecialchars($selection['name'])); ?>
      </td>
      <td name="selected">
        <?php echo ($selection['selected'] ? 'Yes' : 'No') ; ?>
      </td>
      <td>
        <button type="button" class="swap_btn">
          <?php echo ($selection['selected'] ? 'Change to No' : 'Change to Yes') ; ?>
        </button>
      </td>
    </tr>
    <?php } ?>
</table>
</div>
<br>
</main>

<?php include("footer.php"); ?>

<!-- Custom JavaScript goes here -->
<script>
$('.swap_btn').click(function() {
  // Find Current status:
  var cur_field = $(this).closest("tr").find("[name='selected']");
  var cur_text = cur_field.text().trim();

  // And the name that was swapped:
  var name_field = $(this).closest("tr").find("[name='name']");
  var name_text = name_field.text().trim();

  // AJAX request to swap name choice in DB.

  // Show the swap on the page:
  if( cur_text == 'No' ) {
    cur_field.text('Yes');
   $(this).text('Change to No');
    updateNameStatus('yes',name_text);
  } else {
    cur_field.text('No');
   $(this).text('Change to Yes');
    updateNameStatus('no',name_text);
  }

});

function updateNameStatus(status,name) {
  // AJAX Request here
  var data = {"action":'nameRecord', "goodName":status,"name":name};

  // AJAX Request here
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./endpoints/ajax_endpoint.php",
    data: data
  });

}

</script>

</body>

</html>
