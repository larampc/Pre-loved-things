<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/files.php');

$dbh = get_database_connection();

Comment::add_review($dbh, intval($_GET['user']) ,$session->getId(), $_POST['review'], $_POST['stars'] ? intval($_POST['stars']) : 0);

$session->addMessage("success", "Review added successfully");
header('Location: ' . $_SERVER['HTTP_REFERER']);
