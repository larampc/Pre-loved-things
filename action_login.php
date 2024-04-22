<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();
$user = verify_user($dbh, $_POST['email'], $_POST['password']);
if ($user != -1) {
  $_SESSION['user_id'] = $user;
  if ($_SESSION['cart']) add_to_cart($dbh, $_SESSION['cart'], $user);
  header('Location: main.php');
} else  header('Location: login.php');
