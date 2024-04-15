<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();

if (verify_user($dbh, $_POST['username'], $_POST['password'])) {
  $_SESSION['username'] = $_POST['username'];
  header('Location: main.php');
} else  header('Location: login.php');
