<?php
declare(strict_types=1);

require_once (__DIR__ . '/../database/item.class.php');
class TrackItem {

    public array $tracking;
    public string $buyer;
    public string $date;
    public string $state;
    public string $id;
    public string $address;
    public string $city;
    public string $postalCode;
    public function __construct(PDO $db, array $info)
    {
        $items = array();
        foreach (self::get_purchase_items($db, $info['id']) as $item) {
            $items[] = Item::get_item($db, $item['item']);
        }
        $this->tracking = $items;
        $this->buyer = $info['buyer'];
        $this->date = $info['deliveryDate'];
        $this->state = $info['state'];
        $this->id = $info['id'];
        $this->address = $info['address'];
        $this->city = $info['city'];
        $this->postalCode = $info['postalCode'];
    }
    public static function get_tracking_item(PDO $dbh, string $item_track) : TrackItem {
        $stmt = $dbh->prepare('SELECT * FROM purchaseData WHERE id = ?');
        $stmt->execute(array($item_track));
        return new TrackItem($dbh, $stmt->fetch());
    }
    public static function get_pending_purchases_items(PDO $dbh, string $buyer, int $page) : array {
        $page = 5 * ($page - 1);
        $stmt = $dbh->prepare('SELECT purchases.item FROM purchaseData join purchases on purchaseData.id = purchases.purchase WHERE purchaseData.buyer = ? AND purchaseData.state <> ? LIMIT 5 OFFSET ? ');
        $stmt->execute(array($buyer, "delivered", $page));
        $items = $stmt->fetchAll();
        $result = array();
        foreach ($items as $item) {
            $result[] = Item::get_item($dbh, $item['item']);
        }
        return $result;
    }

    public static function get_max_pending_purchases_items(PDO $dbh, string $buyer) : int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM purchaseData join purchases on purchaseData.id = purchases.purchase WHERE purchaseData.buyer = ? AND purchaseData.state <> ?');
        $stmt->execute(array($buyer, "delivered"));
        return intval($stmt->fetchColumn());
    }
    public static function get_purchased_items(PDO $dbh, string $buyer, int $page) : array {
        $page = 5 * ($page - 1);
        $stmt = $dbh->prepare('SELECT purchases.item FROM purchaseData join purchases on purchaseData.id = purchases.purchase WHERE purchaseData.buyer = ? AND purchaseData.state = ? LIMIT 5 OFFSET ? ');
        $stmt->execute(array($buyer, "delivered", $page));
        $items = $stmt->fetchAll();
        $result = array();
        foreach ($items as $item) {
            $result[] = Item::get_item($dbh, $item['item']);
        }
        return $result;
    }
    public static function get_max_purchased_items(PDO $dbh, string $buyer) : int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM purchaseData join purchases on purchaseData.id = purchases.purchase WHERE purchaseData.buyer = ? AND purchaseData.state = ?');
        $stmt->execute(array($buyer, "delivered"));
        return intval($stmt->fetchColumn());
    }
    public static function get_pending_sales_items(PDO $dbh, string $seller, int $page) : array {
        $page = 5 * ($page - 1);
        $stmt = $dbh->prepare('SELECT purchases.item FROM purchaseData join purchases on purchaseData.id = purchases.purchase join items on purchases.item = items.id WHERE items.creator = ? AND purchaseData.state <> ? LIMIT 5 OFFSET ? ');
        $stmt->execute(array($seller, "delivered", $page));
        $items = $stmt->fetchAll();
        $result = array();
        foreach ($items as $item) {
            $result[] = Item::get_item($dbh, $item['item']);
        }
        return $result;
    }

    public static function get_max_pending_sales_items(PDO $dbh, string $seller) : int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM purchaseData join purchases on purchaseData.id = purchases.purchase join items on purchases.item = items.id WHERE items.creator = ? AND purchaseData.state <> ? ');
        $stmt->execute(array($seller, "delivered"));
        return intval($stmt->fetchColumn());
    }
    public static function get_sold_items(PDO $dbh, string $seller, int $page) : array {
        $page = 5 * ($page - 1);
        $stmt = $dbh->prepare('SELECT purchases.item FROM purchaseData join purchases on purchaseData.id = purchases.purchase join items on purchases.item = items.id WHERE items.creator = ? AND purchaseData.state = ? LIMIT 5 OFFSET ? ');
        $stmt->execute(array($seller, "delivered", $page));
        $items = $stmt->fetchAll();
        $result = array();
        foreach ($items as $item) {
            $result[] = Item::get_item($dbh, $item['item']);
        }
        return $result;
    }
    public static function get_max_sold_items(PDO $dbh, string $seller) : int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM purchaseData join purchases on purchaseData.id = purchases.purchase join items on purchases.item = items.id WHERE items.creator = ? AND purchaseData.state = ?');
        $stmt->execute(array($seller, "delivered"));
        return intval($stmt->fetchColumn());
    }

    public static function update_delivery(PDO $dbh, string $purchase, string $date): bool {
        $stmt = $dbh->prepare('UPDATE purchaseData SET deliveryDate=? WHERE id = ?');
        return $stmt->execute(array($date, $purchase));
    }

    public static function get_purchase_items( PDO $dbh, string $id) : array {
        $stmt = $dbh->prepare('SELECT item FROM purchases WHERE purchase = ?');
        $stmt->execute(array($id));
        return $stmt->fetchAll();
    }
    public static function valid_code(PDO $dbh, string $code) : bool {
        $stmt = $dbh->prepare('SELECT * FROM shippingCode WHERE code = ?');
        $stmt->execute(array($code));
        return !empty($stmt->fetchAll());
    }
    public static function update_shipping(PDO $dbh, string $purchase): bool
    {
        $stmt = $dbh->prepare('UPDATE purchaseData SET state=? WHERE id = ?');
        $sale = self::get_tracking_item($dbh, $purchase);
        $state = $sale->state;
        if ($sale->state === "preparing") {$state = "shipping";}
        else if ($sale->state === "shipping") {$state = "delivering";}
        else if ($sale->state === "delivering") {$state = "delivered";}
        if (!$stmt->execute(array($state, $purchase))) return false;
        if ($state === "delivered") {
            $stmt = $dbh->prepare('UPDATE purchaseData SET deliveryDate=? WHERE id = ?');
            return $stmt->execute(array(date("Y-m-d", time()), $purchase));
        }
        return true;
    }
}