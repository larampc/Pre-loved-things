<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');

$dbh = get_database_connection();

dislike_item($dbh, $_SESSION['username'], (int)$_GET['item']);