<?php
declare(strict_types = 1);

session_start();

require_once('templates/item.tpl.php');
require_once('database/item.class.php');
require_once ('database/connection.db.php');

$db = get_database_connection();
$items = Item::get_favorite_items($db, (int)$_SESSION['user_id']);
draw_header("favorite");
if (empty($items)) { ?>
    <h2>Your favorite items</h2>
    <section class="items">
        <p>You have no favorite items yet</p>
    </section>
<?php }
else draw_items($items);
draw_footer();
