
<?php 
function get_database_connection(): PDO {

    $dbh = new PDO('sqlite:'. __DIR__ .  '/../data/preloved.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbh;
}

