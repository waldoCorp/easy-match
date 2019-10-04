<!-- Link Bar -->
<?php
if($_SESSION['login']) { ?>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <a href="./show_names.php">Show Me Names!</a>
    </div>

    <div class="col-sm">
      <a href="./my_names.php">My Names</a>
    </div>

    <div class="col-sm">
      <a href="./match_list.php">List of Matching Names</a>
    </div>

    <div class="col-sm">
      <a href="./logout.php">Logout</a>
    </div>

<?php } else { ?>
    <div class="col-sm">
      <a href="./index.php">My Account/Signup</a>
    </div>
<?php } ?>
  </div>
</div>
