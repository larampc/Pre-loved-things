<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/Comment.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
Comment::add_review($dbh, $_GET['user'] ,$session->get_id(), htmlentities( $_POST['review']), $_POST['stars'] ? intval($_POST['stars']) : 0);

$session->add_message("success", "Review added successfully");
header('Location: ' . $_SERVER['HTTP_REFERER']);
