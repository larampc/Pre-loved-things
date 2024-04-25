<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$item = intval($_POST["edit-item"]);

if (Item::get_item($dbh, $item) !== null) {
    $session->addMessage('error', 'Item not found.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

if (!Item::update_item($dbh, $item, $_POST['iname'], $_POST['description'],  $_POST['price'], $_POST['category'])) {
    $session->addMessage('error', 'Could not update item.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

$session->addMessage('success', 'Item edited successfully.');
header('Location: ../pages/item.php?id=' . $_POST["edit-item"]);