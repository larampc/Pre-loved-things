<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$q = htmlentities($_GET['q']);

if ($q === '') $items = array();
else {
    $dbh = get_database_connection();
    $items = Item::get_items_suggestion($dbh, $q);
}

echo json_encode($items);