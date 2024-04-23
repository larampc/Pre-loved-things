<?php
declare(strict_types=1);

require_once (__DIR__ . '/../database/item.class.php');
class TrackItem {

    public Item $tracking;
    public int $buyer;
    public string $date;
    public string $state;
    public string $code;
    public function __construct(PDO $db, array $info)
    {
        $this->tracking = Item::get_item($db, $info['item']);
        $this->buyer = $info['buyer'];
        $this->date = $info['deliveryDate'];
        $this->state = $info['state'];
        $this->code = $info['code'];
    }
    public static function get_tracking_item(PDO $dbh, int $item_track) : TrackItem {
        $stmt = $dbh->prepare('SELECT * FROM purchases WHERE item = ?');
        $stmt->execute(array($item_track));
        return new TrackItem($dbh, $stmt->fetch());
    }
    public static function get_purchased_items(PDO $dbh, int $buyer) : array {
        $stmt = $dbh->prepare('SELECT * FROM purchases WHERE buyer = ?');
        $stmt->execute(array($buyer));
        $purchases = $stmt->fetchAll();
        $result = array();
        foreach ($purchases as $purchase) {
            $result[] = new TrackItem($dbh, $purchase);
        }
        return $result;
    }

    public static function get_selling_items(PDO $dbh, int $seller) : array {
        $stmt = $dbh->prepare('SELECT * FROM purchases LEFT JOIN items ON purchases.item = items.id WHERE items.creator = ?');
        $stmt->execute(array($seller));
        $purchases = $stmt->fetchAll();
        $result = array();
        foreach ($purchases as $purchase) {
            $result[] = new TrackItem($dbh, $purchase);
        }
        return $result;
    }

    public static function update_delivery(PDO $dbh, int $item, string $date) {
        $stmt = $dbh->prepare('UPDATE purchases SET deliveryDate=? WHERE item = ?');
        $stmt->execute(array($date, $item));
    }
}