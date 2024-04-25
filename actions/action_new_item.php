<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/files.php');

$dbh = get_database_connection();

upload_image('img1');
upload_image('img2');

$item_id = Item::register_item($dbh, $_POST['iname'], $_POST['description'],  $_POST['price'], $_POST['category'], $session->getId(), $_FILES['img1']['name']);

if ($item_id == -1) {
    //remove images
    $session->addMessage('error', 'Error creating item.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

if (!Item::register_item_images($dbh, array($_FILES['img1']['name'], $_FILES['img2']['name']), $item_id)) {
    $session->addMessage('error', 'Error creating item.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

$session->addMessage('success', 'You item is now for sale.');
header('Location: ../pages/profile.php');