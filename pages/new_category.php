<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
require_once(__DIR__ . '/../database/tags.class.php');
require_once(__DIR__ . '/../database/connection.db.php');


require_once(__DIR__ . '/../templates/item.tpl.php');
$dbh = get_database_connection();

if (!$session->isLoggedIn() || User::get_user($dbh, $session->getId())->role != "admin") die(header('Location: /'));


get_header("new-category", $dbh, $session);
draw_new_category_form();
draw_footer();