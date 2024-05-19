<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$category = $_POST['remove-category'];


if (Tag::delete_category($dbh, $category)) {
    $session->add_message('success', 'Category deleted successfully.');
}
else {
    $session->add_message('error', 'Unable to delete category.');
}

header('Location: ../pages/search.php' );