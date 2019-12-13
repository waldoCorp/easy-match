<!-- Link Bar -->
<!-- other background color choices?

#e3f2fd

-->
<header>
<nav class="navbar navbar-expand-md navbar-light" style="background-color: #d6d2f7">
  <a class="navbar-brand mb-01 h1" href="about.php">Baby Names</a>

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
      <a class="nav-link" href="partner_actions.php">Invite Partner!</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="match_list.php">My Matches!</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="popularity_charts.php">Popularity Charts</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="account.php">My Account</a>
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

  <?php } ?>
  </ul>
 </div>
</nav>
</header>

<br>
