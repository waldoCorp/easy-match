<?php
if(!$_SESSION['login']) {
  $_SESSION['target_page'] = $_SERVER['REQUEST_URI'];
  header('Location: ./index.php');
}
