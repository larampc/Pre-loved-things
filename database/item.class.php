<?php
declare(strict_types = 1);

require_once ('tags.class.php');
class Item {
    public int $id;
    public string $name;
    public array $images;
    public string $mainImage;
    public float $price;
    public string $description;
    public string $category;
    public array $tags;
    public string $condition;
    public string $date;
    public int $creator;
    public function __construct(PDO $db, int $id)
    {
        $this->id = $id;
        $this->images = get_item_images($db, $id);
    }

    public static function create_item(PDO $dbh, array $item): Item
    {
        $new_item = new Item($dbh, $item['id']);
        $new_item->condition = $item['condition'] != null ? $item['condition'] : "";
        $new_item->price = $item['price'] != null ? $item['price'] : 0.0;
        $new_item->description = $item['description'] != null ? $item['description'] : "";
        $new_item->name = $item['name'] != null ? $item['name'] : "";
        $new_item->creator =$item['creator'];
        $new_item->mainImage = $item['mainImage']!= null ? $item['mainImage'] : "";;
        $new_item->category = Tag::get_item_category($dbh, $item['id']);
        $new_item->tags = Tag::get_item_tags($dbh, $item['id']);
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

    public static function update_item(PDO $dbh, int $id,  string $name, string $description, string $price): bool
    {
        $stmt = $dbh->prepare('UPDATE items SET name = ?, description = ?, price = ? WHERE id = ?');
        return $stmt->execute(array($name, $description, floatval(str_replace(',', '.', $price)),$id));
    }

    static function get_item_images(PDO $dbh, int $id) : array
    {
        $stmt = $dbh->prepare('SELECT imagePath FROM item_images WHERE item = ?');
        $stmt->execute(array($id));
        $images = array();
        while ($image = $stmt->fetch()) {
            $images[] = $image['imagePath'];
        }
        return $images;
    }
    static function get_items(PDO $dbh, int $count) : array {
        $stmt = $dbh->prepare('SELECT * FROM items LIMIT ?');
        $stmt->execute(array($count));
        return self::create_items($dbh, $stmt->fetchAll());
    }
    static function get_items_category(PDO $dbh, string $category) : array {
        $stmt = $dbh->prepare('SELECT item FROM tags_values join tags on tags.id = tags_values.tag join categories on categories.id = tags.category where categories.category = ?');
        $stmt->execute(array($category));
        $res = $stmt->fetchAll();
        return  $res? self::create_items($dbh, self::get_items_in_array($dbh, $res)) : array();
    }
    static function get_item(PDO $dbh, int $id) : ?Item{
        $stmt = $dbh->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute(array($id));
        $item = $stmt->fetch();
        if (!$item) return null;
        return self::create_item($dbh, $item);
    }

    static function get_user_items(PDO $dbh, int $user_id): array {
        $stmt = $dbh->prepare('SELECT * FROM items WHERE creator = ?');
        $stmt->execute(array($user_id));
        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function get_items_by_search(PDO $dbh, string $q): array
    {
        $stmt = $dbh->prepare(
            'SELECT * 
                FROM items 
                WHERE name LIKE ?'
        );
        $stmt->execute(array("%$q%"));

        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function get_items_in_page(PDO $dbh, int $page): array
    {
        $offset = 18 * ($page-1);
        $stmt = $dbh->prepare('SELECT * FROM items LIMIT 18 OFFSET ?');
        $stmt->execute(array($offset));
        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function get_items_by_category(PDO $dbh, string $q, string $cat): array
    {
        $stmt = $dbh->prepare(
            'SELECT * 
                FROM items 
                WHERE name LIKE ? AND category = ?'
        );
        $stmt->execute(array("%$q%", $cat));

        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function get_items_by_range(PDO $dbh, int $first, int $second): array
    {
         $stmt = $dbh->prepare(
            'SELECT * 
                FROM items 
                WHERE price >= ? AND price <= ?'
        );
        $stmt->execute(array($first, $second));

        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function get_items_by_condition(PDO $dbh, string $condition): array
    {
        $stmt = $dbh->prepare('SELECT * FROM items WHERE condition = ?');
        $stmt->execute(array($condition));

        return self::create_items($dbh, $stmt->fetchAll());
    }


    static function get_favorite_items(PDO $dbh, int $user_id): array
    {
        $stmt = $dbh->prepare('SELECT *
        FROM items LEFT JOIN favorites 
        ON favorites.item = items.id  WHERE favorites.user = ?');
        $stmt->execute(array($user_id));

        return self::create_items($dbh, $stmt->fetchAll());
    }

    static function check_favorite(PDO $dbh, int $user_id, Item $item): bool
    {
        $stmt = $dbh->prepare('SELECT *
        FROM favorites WHERE user = ? AND item = ?');
        $stmt->execute(array($user_id, $item->id));
        return !empty($stmt->fetchAll());
    }

    static function check_cart(PDO $dbh, int $user_id, Item $item): bool
    {
        $stmt = $dbh->prepare('SELECT *
        FROM user_cart WHERE user = ? AND item = ?');
        $stmt->execute(array($user_id, $item->id));
        return !empty($stmt->fetchAll());
    }
    static function get_items_user(PDO $dbh, string $user): array
    {
        $stmt = $dbh->prepare('SELECT * FROM items WHERE creator = ?');
        $stmt->execute(array($user));

        return self::create_items($dbh, $stmt->fetchAll());
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

    static function register_item(PDO $db, string $name, string $description, string $price, string $category, int $user_id, string $mainImage): int {
        $stmt = $db->prepare('INSERT INTO items (name, description, price, category, creator, mainImage) VALUES (?, ?, ?, ?, ?, ?)');
        $ret = $stmt->execute([$name, $description, floatval(str_replace(',', '.', $price)), $category, $user_id, $mainImage]);
        $stmt = $db->prepare('SELECT last_insert_rowid()');
        $stmt->execute();
        return $ret ? $stmt->fetchColumn() : -1;
    }
    static function register_item_images(PDO $db, array $images, int $item_id): bool
    {
        foreach ($images as $image) {
            $stmt = $db->prepare('INSERT INTO item_images (item, imagePath) VALUES (?, ?)');
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
}
