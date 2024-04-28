<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$item = intval($_POST['remove-item']);


if (Item::delete_item($dbh, $item)) {
    $session->addMessage('success', 'Item deleted successfully.');
}
else {
    $session->addMessage('error', 'Unable to delete item.');
}

header('Location: ../pages' );