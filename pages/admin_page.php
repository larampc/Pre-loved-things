<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn() || !$session->is_admin()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/item.class.php');

require_once(__DIR__ . '/../templates/user.tpl.php');
require_once(__DIR__ . '/../templates/common.tpl.php');


$dbh = get_database_connection();

get_header("admin", $dbh, $session);
draw_admin_page();
draw_footer();