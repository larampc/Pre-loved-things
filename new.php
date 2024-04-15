<?php

declare(strict_types=1);

session_start();

require_once('templates/item.tpl.php');

draw_header("new");
draw_new_item_form();
draw_footer();