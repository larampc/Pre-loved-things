
<?php 
function get_database_connection(): PDO {

    $dbh = new PDO('sqlite:data/preloved.db');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbh;
}
function get_item_images(PDO $dbh, int $id) : array
{
    $stmt = $dbh->prepare('SELECT imagePath FROM item_images WHERE item = ?');
    $stmt->execute(array($id));
    $images = array();
    while ($image = $stmt->fetch()) {
        $images[] = $image['imagePath'];
    }
    return $images;
}
