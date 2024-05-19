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

if (Tag::exists_category($dbh, $_POST['category'])) {
    $session->add_message("error", "Category already exists.");
    die (header('Location: ' . $_SERVER['HTTP_REFERER']));
}
$category_id = Tag::add_category($dbh, $_POST['category']);
$tags = $_POST['tags'] ? array_filter($_POST['tags']) : array();
Tag::add_tags_category($dbh, $category_id, $tags);
foreach ($tags as $key => $value) {
    if ($_POST['option' . $key] != NULL) {
        Tag::add_tag_options($dbh, array_filter($_POST['option' . $key]), Tag::get_tag_id($dbh, $_POST['category'],$value));
    }
}

header('Location: ../pages/search.php');
