<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();

add_cart($dbh, (int)$_SESSION['user_id'], (int)$_GET['item']);