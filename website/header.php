<!-- Link Bar -->
<div class="container">
  <div class="row">
  <?php if($_SESSION['login']) { ?>
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
      <a href="./partner_actions.php">Manage Partners</a>
    </div>

    <div class="col-sm">
      <a href="./about.php">About Baby Names Match</a>
    </div>

    <div class="col-sm">
      <a href="./logout.php">Logout</a>
    </div>

  <?php } else { ?>
    <div class="col-sm">
      <a href="./index.php">My Account/Signup</a>
    </div>

    <div class="col-sm">
      <a href="./about.php">About Baby Names Match</a>
    </div>
  <?php } ?>
  </div>
</div>
