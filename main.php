<?php
    declare(strict_types = 1);

    require_once('templates/common.tpl.php');
    require_once('templates/item.tpl.php');

    require_once('database/item.db.php');
    require_once('database/connection.db.php');

    $db = getDatabaseConnection();
    $items = getItems($db);

    drawHeader();
    drawItems($items);
    drawFooter();
