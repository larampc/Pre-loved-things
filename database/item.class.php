<?php
declare(strict_types = 1);

require_once ('tags.class.php');
require_once ('user.class.php');
require_once ('../utils/uuid.php');

class Item {
    public string $id;
    public string $name;
    public array $images;
    public string $mainImage;
    public float $price;
    public string $description;
    public string $category;
    public array $tags;
    public string $date;
    public User $creator;
    public bool $sold;
    public function __construct(PDO $db, string $id)
    {
        $this->id = $id;
        $this->images = self::get_item_images($db, $id);
    }

    public static function create_item(PDO $dbh, array $item): Item
    {
        $new_item = new Item($dbh, $item['id']);
        $new_item->price = $item['price'] ?? 0.0;
        $new_item->description = $item['description'] ?? "";
        $new_item->name = $item['name'] ?? "";
        $new_item->creator = User::get_user($dbh, $item['creator']);
        $new_item->mainImage = $item['mainImage'] ?? "";;
        $new_item->category = Tag::get_category_by_id($dbh, $item['category']);
        $new_item->tags = Tag::get_item_tags($dbh, $item['id']);
        $new_item->sold = boolval($item['sold']);
        return $new_item;
    }

    public static function create_items(PDO $dbh, array $items): array
    {
        $new_items = array();
        foreach ($items as $item) {
            $new_items[] = self::create_item($dbh, $item);
        }
        return $new_items;
    }

    public static function update_item(PDO $dbh, string $id,  string $name, string $description, float $price, string $category): bool
    {
        $stmt = $dbh->prepare('UPDATE items SET name = ?, description = ?, price = ?, category = ? WHERE id = ?');
        return $stmt->execute(array($name, $description, $price, $category,$id));
    }
    public static function update_item_images(PDO $dbh, string $id, string $mainImage, array $images): bool
    {
        $oldImages = self::get_item_images($dbh, $id);
        $stmt = $dbh->prepare('DELETE FROM item_images WHERE item = ?');
        $stmt->execute([$id]);

        self::register_item_images($dbh, $images ,$id);

        $stmt = $dbh->prepare('UPDATE items SET mainImage = ? WHERE id = ?');
        $stmt->execute([$mainImage,$id]);

        return true;
    }

    static function get_item_images(PDO $dbh, string $id) : array
    {
        $stmt = $dbh->prepare('SELECT image FROM item_images WHERE item = ?');
        $stmt->execute(array($id));
        $images = array();
        while ($image = $stmt->fetch()) {
            $images[] = $image['image'];
        }
        return $images;
    }
    static function get_item(PDO $dbh, string $id) : ?Item{
        $stmt = $dbh->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute(array($id));
        $item = $stmt->fetch();
        if (!$item) return null;
        return self::create_item($dbh, $item);
    }

    static function get_user_items(PDO $dbh, string $user_id, int $page = 1): array {
        $page = 6 * ($page - 1);
        $stmt = $dbh->prepare('SELECT * FROM items WHERE creator = ? AND sold = 0 LIMIT 6 OFFSET ?');
        $stmt->execute(array($user_id, $page));
        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function get_max_user_items(PDO $dbh, string $user_id): int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items WHERE creator = ? AND sold = 0');
        $stmt->execute(array($user_id));
        return intval($stmt->fetchColumn());
    }

    static function get_items_suggestion(PDO $dbh, string $q): array
    {
        $stmt = $dbh->prepare(
            'SELECT items.name 
                FROM items 
                WHERE name LIKE ? OR name LIKE ?'
        );
        $stmt->execute(array("$q%", "% $q%"));
        return array_unique($stmt->fetchAll());
    }

    static function get_favorite_items(PDO $dbh, string $user_id): array
    {
        $stmt = $dbh->prepare('SELECT *
        FROM items LEFT JOIN favorites 
        ON favorites.item = items.id  WHERE favorites.user = ?');
        $stmt->execute(array($user_id));

        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function check_favorite(PDO $dbh, string $user_id, Item $item): bool
    {
        $stmt = $dbh->prepare('SELECT *
        FROM favorites WHERE user = ? AND item = ?');
        $stmt->execute(array($user_id, $item->id));
        return !empty($stmt->fetchAll());
    }

    static function check_cart(PDO $dbh, string $user_id, Item $item): bool
    {
        $stmt = $dbh->prepare('SELECT *
        FROM user_cart WHERE user = ? AND item = ?');
        $stmt->execute(array($user_id, $item->id));
        return !empty($stmt->fetchAll());
    }

    static function get_items_in_array(PDO $dbh, array $items): array
    {   $res = array();
        foreach ($items as $item) {
            $stmt = $dbh->prepare('SELECT * FROM items WHERE id = ?');
            $stmt->execute(array($item));
            $it =  $stmt->fetch();
            if ($it) $res[] = $it;
        }
        return empty($res) ? $res: self::create_items($dbh, $res);
    }

    static function register_item(PDO $dbh, string $name, string $description, float $price, string $category, string $user_id, string $mainImage): string {
        $id = generate_uuid();
        $stmt = $dbh->prepare('INSERT INTO items (id, name, description, price, creator, mainImage, category, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $ret = $stmt->execute([$id,$name, $description, $price, $user_id, $mainImage, $category, date('Y-m-d', time())]);
        return $ret ? $id : "";
    }
    static function register_item_images(PDO $db, array $images, string $item_id): bool
    {
        foreach ($images as $image) {
            $stmt = $db->prepare('INSERT INTO item_images (item, image) VALUES (?, ?)');
            if (!$stmt->execute([$item_id, $image])) return false;
        }
        return true;
    }
    static function sort_by_user(array $items): array
    {
        uasort($items, function($a, $b) {
            if ($a->creator < $b->creator) return -1;
            else if ($a->creator == $b->creator) return 0;
            else return 1;
        });
        return $items;
    }
    static function get_filtered_items(PDO $dbh, array $categories, array $itemTags, int $page, bool $checkTag, int $min, int $max, string $search, string $order): array {
        $getOrder = "date ASC";
        if ($order == "price-asc") $getOrder = "price ASC";
        if ($order == "price-desc") $getOrder = "price DESC";
        $page = 20 * ($page - 1);
        if ($checkTag) {
            $stmt = $dbh->prepare("SELECT * FROM items 
             WHERE category IN (". "'". implode("' ,'", $categories). "'" . " ) 
             AND id IN (". "'". implode("' ,'", $itemTags). "'"  . " )
             AND price >= ? AND price <= ? 
             AND (name LIKE ? OR name LIKE ?) AND sold = 0
            ORDER BY ". $getOrder ." LIMIT 20 OFFSET ? ");
        }
        else {
            $stmt = $dbh->prepare("SELECT * FROM items 
             WHERE category IN (". "'". implode("' ,'", $categories). "'" . " ) 
             AND price >= ? AND price <= ? 
             AND (name LIKE ? OR name LIKE ?) AND sold = 0
             ORDER BY ". $getOrder ." LIMIT 20 OFFSET ?");
        }
        $stmt->execute(array($min, $max, "$search%", "% $search%", $page));
        return self::create_items($dbh, $stmt->fetchAll());
    }
    static function get_most_liked_items(PDO $dbh, int $count = 5): array
    {
        $stmt = $dbh->prepare('SELECT items.id as id FROM items LEFT JOIN favorites ON favorites.item = items.id 
         where items.sold = 0 GROUP BY items.id ORDER BY count(favorites.user) DESC LIMIT ?');
        $stmt->execute(array($count));
        $items = array();
        while($item = $stmt->fetch()) {
            $items[] = self::get_item($dbh, $item['id']);
        }
        return $items;
    }
    static function get_last_added_items(PDO $dbh, int $count = 5): array
    {
        $stmt = $dbh->prepare('SELECT * FROM items WHERE sold = 0 ORDER BY date DESC LIMIT ?');
        $stmt->execute(array($count));
        return self::create_items($dbh, $stmt->fetchAll());
    }
    static function get_purchase_id(PDO $dbh, string $item): string {
        $stmt = $dbh->prepare('SELECT purchase FROM purchases WHERE item = ?');
        $stmt->execute(array($item));
        return $stmt->fetchColumn();
    }

    static function update_item_sold(PDO $dbh, string $id): void {
        $stmt = $dbh->prepare('UPDATE items SET sold = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
    static function update_items_sold(PDO $dbh, array $items) {
        foreach ($items as $item) {
            self::update_item_sold($dbh, $item->id);
        }
    }

    static function register_purchase(PDO $dbh, string $buyer, array $items, string $address, string $city, string $postalCode): string {
        $id = generate_uuid();
        $stmt = $dbh->prepare("INSERT INTO purchaseData (id, buyer, deliveryDate, state, address, city, postalCode) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(array($id, $buyer,date('Y-m-d', time()+10*60*60*24), 'preparing',$address, $city, $postalCode));
        foreach ($items as $item) {
            $stmt = $dbh->prepare('INSERT INTO purchases VALUES(?, ?)');
            $stmt->execute(array($item->id, $id));
        }
        return $id;
    }

    static function remove_cart_favorite(PDO $dbh, array $items) {
        foreach ($items as $item) {
            $stmt = $dbh->prepare('DELETE FROM user_cart WHERE item = ?');
            $stmt->execute(array($item->id));
            $stmt = $dbh->prepare('DELETE FROM favorites WHERE item = ?');
            $stmt->execute(array($item->id));
        }
    }

    static function delete_item(PDO $dbh, string $id): bool
    {
        $stmt = $dbh->prepare('DELETE FROM items WHERE id = ?');
        if (!$stmt->execute(array($id))) return false;
        self::remove_cart_favorite($dbh, array(self::get_item($dbh, $id)));
        Tag::remove_item_tags($dbh, $id);
        return true;
    }
    static function get_number_likes(PDO $dbh, Item $item) {
        $stmt = $dbh->prepare('SELECT count(*) FROM items JOIN favorites ON favorites.item = items.id 
         where items.id = ? ');
        $stmt->execute(array($item->id));
        return $stmt->fetchColumn();
    }
    static function get_sell_items(PDO $dbh, string $month) {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items WHERE date = ?');
        $stmt->execute(array("%/$month/".date("Y", time())));
        return $stmt->fetchColumn();
    }
    static function get_buy_items(PDO $dbh, string $month) {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items JOIN purchases on items.id = purchases.item JOIN purchaseData on purchases.purchase = purchaseData.id WHERE purchaseData.deliveryDate LIKE ?');
        $stmt->execute(array("%/$month/".date("Y", time())));
        return $stmt->fetchColumn();
    }
}
