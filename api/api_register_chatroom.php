<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/chatroom.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/user.class.php');

$dbh = get_database_connection();
echo json_encode(Chatroom::save_chatroom($dbh, intval($_GET['item_id']), intval($_GET['seller_id']),intval($_SESSION['user_id'])));

