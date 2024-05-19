<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/files.php');
require_once(__DIR__ . '/../database/currency.class.php');
$session = new Session();

if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$item = $_POST["edit-item"];

$old_images = Item::get_item_images($dbh, $item);
$max_num_saved_img = count($old_images);
$new_images_ids = array();
foreach ($_FILES as $key => $value) {
    $position = substr($key, 3);
    if(empty($value['tmp_name'])) {
        $new_images_ids[] = $_POST['hiddenimg' . $position];
    }
    else {
        $new_images_ids[] = upload_item_image($key);
    }
}
$img_to_remove = array_diff($old_images, $new_images_ids);

remove_uploaded_item_imgs($img_to_remove);

if (Item::get_item($dbh, $item) === null) {
    $session->add_message('error', 'Item not found.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}
Tag::remove_item_tags($dbh, $item);
if (!Item::update_item($dbh, $item, htmlentities($_POST['item-name']), htmlentities($_POST['description']), round($_POST['price']/ Currency::get_currency_conversion($dbh, $session->get_currency()), 2),  $_POST['category'])
|| !Item::update_item_images($dbh, $item, $new_images_ids[0], $new_images_ids)) {
    $session->add_message('error', 'Could not update item.');
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
$session->add_message('success', 'Item edited successfully.');
header('Location: ../pages/item.php?id=' . $_POST["edit-item"]);
