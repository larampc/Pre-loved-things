<?php

declare(strict_types=1);
session_start();

require_once('database/users.db.php');
require_once('templates/user.tpl.php');
require_once(__DIR__ . '/database/connection.db.php');


$db = get_database_connection();
draw_header("editprofile");
draw_edit_profile(get_user($db, (int)$_SESSION['user_id']));
draw_footer();