<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

Item::update_item($dbh, intval($_POST["edit-item"]), $_POST['iname'], $_POST['description'],  $_POST['price'], $_POST['category']);

header('Location: ../pages/item.php?id=' . $_POST["edit-item"]);