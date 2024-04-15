<?php

declare(strict_types=1);

session_start();

require_once('templates/users.tpl.php');

draw_header();
draw_login_form();
draw_footer();