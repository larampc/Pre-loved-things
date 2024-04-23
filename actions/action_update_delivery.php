<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/track_item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

TrackItem::update_delivery($dbh, intval($_POST["item"]), $_POST['new-date']);

header('Location: ../pages/track_item.php?item-track=' . $_POST["item"]);