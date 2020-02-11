<!-- Link Bar -->
<!-- other background color choices?

#e3f2fd

-->
<header>
<nav class="navbar navbar-expand-md navbar-light" style="background-color: #d6d2f7">
  <a class="navbar-brand mb-01 h1" href="about.php">Easy Match</a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">

<!--  <ul class="navbar-nav nav-fill w-100"> -->
  <ul class="navbar-nav mr-auto">

  <?php if($_SESSION['login']) { ?>
    <li class="nav-item">
      <a class="nav-link" href="show_names.php">Show Me Names</a>
    </li>

    <li class="nav-item">
     <?php if( $_SESSION['new_invitations'] ) { ?>
            <i data-feather="user-plus" id="tooltip-invite" data-toggle="tooltip"
               data-placement="bottom" title="New Partner Invitation"></i>
            <p class="sr-only sr-only-focusable">You have invitations to partner</p>
     <?php } ?>
      <a class="nav-link" href="partner_actions.php">Invite Partner</a>
    </li>

    <li class="nav-item">
            <i data-feather="star" id="tooltip-matches" data-toggle="tooltip"
               data-placement="bottom" title="New Name Matches"></i>
            <p class="sr-only sr-only-focusable" id="newMatchSR"></p>
      <a class="nav-link" href="match_list.php">My Matches</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="popularity_charts.php">Name Trends</a>
    </li>
  </ul>

  <ul class="navbar-nav">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Account
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="account.php">My Account</a>
        <a class="dropdown-item" href="logout.php">Logout</a>
      </div>
    </li>
  </ul>
  <?php } else { ?>
    <li class="nav-item">
      <a class="nav-link" href="index.php">My Account/Signup</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="popularity_charts.php">Name Trends</a>
    </li>

  </ul>
  <?php } ?>
 </div>
</nav>
</header>

<br>
