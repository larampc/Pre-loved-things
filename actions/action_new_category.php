<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/files.php');

$dbh = get_database_connection();


$category_id = Tag::add_category($dbh, $_POST['category']);
$tags = $_POST['tags'];
Tag::add_tags_category($dbh, intval($category_id), $tags);
for ($i = 0; $i < count($tags); $i++) {
    if ($_POST['option' . $i] != NULL) {
        Tag::add_tag_options($dbh, $_POST['option' . $i], Tag::get_tag_id($dbh, $_POST['category'],$tags[$i]));
    }
}

header('Location: ../pages/search.php');
