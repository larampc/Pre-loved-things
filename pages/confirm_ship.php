<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/track_item.class.php');

$dbh = get_database_connection();

$track_item = TrackItem::get_tracking_item($dbh, $_GET['purchase']);
get_header("sale-info", $dbh, $session);
draw_confirm_ship($track_item);
draw_footer();