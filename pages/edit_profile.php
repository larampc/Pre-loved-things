<?php

declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

require_once(__DIR__ . '/../templates/user.tpl.php');

$db = get_database_connection();
draw_header("editprofile");
draw_edit_profile(User::get_user($db, (int)$_SESSION['user_id']));
draw_footer();