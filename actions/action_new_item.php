<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/files.php');

$dbh = get_database_connection();


$img_ids = array();
foreach ($_FILES as $name => $file) {
    if(!empty($file['tmp_name'])) $img_ids[] = upload_item_image($name);
}

$item_id = Item::register_item($dbh, htmlentities($_POST['item-name']), htmlentities($_POST['description']),  round($_POST['price']/ User::get_currency_conversion($dbh, $session->getCurrency()), 2), $_POST['category'] == "other" ? "" : $_POST['category'], $session->getId(), $img_ids[0]);

if ($item_id == -1) {
    //remove images
    $session->addMessage('error', 'Error creating item.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

$tags = Tag::get_category_tags($dbh, $_POST['category']??"");
foreach ($tags as $tag) {
    if ($_POST[$tag['id']]) {
        Tag::register_item_tags($dbh, $tag['id'], $item_id, $_POST[$tag['id']]);
    }
}
if (isset($_POST['category'])) {
    $tags = Tag::get_category_tags($dbh, "");
    foreach ($tags as $tag) {
        if ($_POST[$tag['id']]) {
            Tag::register_item_tags($dbh, $tag['id'], $item_id, $_POST[$tag['id']]);
        }
    }
}

if (!Item::register_item_images($dbh, $img_ids, $item_id)) {
    $session->addMessage('error', 'Error creating item.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

$session->addMessage('success', 'You item is now for sale.');
header('Location: ../pages/profile.php');