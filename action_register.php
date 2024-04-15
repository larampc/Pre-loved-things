<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();

register_user($dbh, $_POST['username'], $_POST['password'], $_POST['email']);
header('Location: login.php');
