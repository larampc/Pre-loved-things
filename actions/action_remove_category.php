<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

//if ($_SESSION['csrf'] !== $_POST['csrf']) {
//    $session->addMessage('error', 'Illegitimate request.');
//    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
//}

require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$category = $_GET['category'];


if (Tag::delete_category($dbh, $category)) {
    $session->addMessage('success', 'Category deleted successfully.');
}
else {
    $session->addMessage('error', 'Unable to delete category.');
}

header('Location: ../pages/search.php' );