<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf=8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap Sourcing, CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<title>Demo Page</title>
</head>

<body>

<!-- Link Bar -->
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
      <a href="./index.php">My Account/Signup</a>
    </div>
  </div>
</div>

<div class="container">
  <h2>Welcome to Baby Names Match!</h2>
  <form>
    <fieldset>
      <legend>Sign in (or <a href="new_account.php">create account</a>)</legend>
      <p>
        <label for="email">Email</label>
        <input id="email" type="email" placeholder="Enter Email Address">
      </p>

      <p>
        <label for="password">Email</label>
        <input id="password" type="password">
      </p>

      <p>
        <button type="submit" value="Submit" action="./endpoints/user_endpoint.php">Log In</button>
      </p>

    </fieldset>
  </form>


  <form>
    <fieldset>
      <legend>Add a friend to compare matches!</legend>
      <p>
        <label for="email">Email</label>
        <input id="email" type="email" placeholder="Enter Email Address">
      </p>

      <p>
        <button type="submit" value="Submit" action="./endpoints/ajax_endpoint.php">Add Friend!</button>
      </p>

    </fieldset>
  </form>

</div>


<!-- Bootstrap Sourcing, jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
