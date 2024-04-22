<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();
$user = verify_user($dbh, $_POST['email'], $_POST['password']);
if (!empty($user)) {
  $_SESSION['user_id'] = $user['user_id'];
  var_dump($_SESSION['cart']);
  if ($_SESSION['cart']) add_to_cart($dbh, $_SESSION['cart'], $user['user_id']);
  header('Location: main.php');
} else  header('Location: login.php');
