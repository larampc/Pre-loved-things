<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if(!validateCsrfToken($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$item = $_POST["edit-item"];

if (Item::get_item($dbh, $item) === null) {
    $session->addMessage('error', 'Item not found.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}
Tag::remove_item_tags($dbh, $item);
if (!Item::update_item($dbh, $item, $_POST['iname'], $_POST['description'], round($_POST['price']/ User::get_currency_conversion($dbh, $session->getCurrency()), 2),  $_POST['category'])) {
    $session->addMessage('error', 'Could not update item.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

$tags = Tag::get_category_tags($dbh, $_POST['category']);
foreach ($tags as $tag) {
    if ($_POST[$tag['id']]) {
        Tag::register_item_tags($dbh, $tag['id'], $item, $_POST[$tag['id']]);
    }
}
if ($_POST['category']) {
    $tags = Tag::get_category_tags($dbh, "");
    foreach ($tags as $tag) {
        if ($_POST[$tag['id']]) {
            Tag::register_item_tags($dbh, $tag['id'], $item, $_POST[$tag['id']]);
        }
    }
}
$session->addMessage('success', 'Item edited successfully.');
header('Location: ../pages/item.php?id=' . $_POST["edit-item"]);