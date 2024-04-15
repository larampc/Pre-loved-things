<?php

declare(strict_types=1);

session_start();

require_once('database/item.db.php');

$dbh = get_database_connection();

insert_item($dbh, $_POST['iname'], $_POST['description'],  $_POST['price'], $_POST['category'], $_SESSION['username']);
header('Location: profile.php');