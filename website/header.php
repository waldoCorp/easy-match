<!-- Link Bar -->
<!-- other background color choices?

#e3f2fd

-->

<nav class="navbar navbar-expand-sm navbar-light" style="background-color: #d6d2f7">
  <span class="navbar-brand mb-01 h1">Baby Names</span>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">

  <ul class="navbar-nav nav-fill w-100">

  <?php if($_SESSION['login']) { ?>
    <li class="nav-item">
      <a class="nav-link" href="show_names.php">Show Me Names!</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="match_list.php">My Matches!</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="popularity_charts.php">Popularity Charts</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="my_names.php">Manage Names</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="partner_actions.php">Manage Partners</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="about.php">About</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="logout.php">Logout</a>
    </li>

  <?php } else { ?>
    <li class="nav-item">
      <a class="nav-link" href="index.php">My Account/Signup</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="popularity_charts.php">Popularity Charts</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="about.php">About</a>
    </li>
  <?php } ?>
  </ul>
 </div>
</nav>

<br>
