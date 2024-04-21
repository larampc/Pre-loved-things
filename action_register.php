<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();

register_user($dbh, $_POST['password'], $_POST['email'], $_POST['name'], $_POST['phone']);
header('Location: login.php');
