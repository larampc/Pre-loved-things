<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/currency.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$symbol = Currency::get_currency_symbol($dbh, $session->get_currency());


echo json_encode($symbol);