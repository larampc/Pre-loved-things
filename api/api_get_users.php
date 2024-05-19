<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$page = $_GET['page'];
$dbh = get_database_connection();

echo json_encode(User::get_users($dbh, intval($page), htmlentities($_GET['search'])));