<?php
declare(strict_types = 1);
require_once ('database/connection.db.php');

class Item {
    public int $id;
    public string $name;
    public array $images;
    public float $price;
    public string $description;
    public string $category;
    public int $quantity;
    public string $model;
    public string $brand;
    public string $condition;
    public string $size;
    public string $date;
    public string $user;
    public function __construct(PDO $db, int $id)
    {
        $this->id = $id;
        $this->images = get_item_images($db, $id);
    }

    public static function create_items(PDO $dbh, $stmt): array
    {
        $items = array();
        while ($item = $stmt->fetch()) {
            $new_item = new Item($dbh, $item['id']);
            $new_item->setBrand($item['brand'])
                ->setCondition($item['condition'])
                ->setSize($item['size'])
                ->setModel($item['model'])
                ->setCategory($item['category'])
                ->setName($item['name'])
                ->setDescription($item['description'])
                ->setPrice($item['price'])
                ->setUser($item['user'])
                ->setQuantity($item['quantity'])
                ->setModel($item['model']);
            $items[] = $new_item;
        }
        return $items;
    }

    public function get_main_image(): string
    {
        return $this->images[array_key_first($this->images)];
    }
    public function setBrand(?string $brand): Item
    {
        if($brand == null) return $this;
        $this->brand = $brand;
        return $this;
    }
    public function setCondition(?string $condition): Item {
        if($condition == null) return $this;
        $this->condition = $condition;
        return $this;
    }
    public function setSize(?string $size): Item {
        if($size == null) return $this;
        $this->size = $size;
        return $this;
    }
    public function setDate(?string $date): Item {
        if ($date == null) return $this;
        $this->date = $date;
        return $this;
    }
    public function setQuantity(?int $quantity): Item {
        if($quantity == null) return $this;
        $this->quantity = $quantity;
        return $this;
    }
    public function setModel(?string $model): Item {
        if($model == null) return $this;
        $this->model = $model;
        return $this;
    }
    public function setCategory(?string $category): Item {
        if($category == null) return $this;
        $this->category = $category;
        return $this;
    }
    public function setName(string $name): Item {
        $this->name = $name;
        return $this;
    }
    public function setDescription(?string $description): Item
    {
        if($description == null) return $this;
        $this->description = $description;
        return $this;
    }
    public function setUser(string $user): Item
    {
        $this->user = $user;
        return $this;
    }
    public function setPrice(float $price): Item
    {
        $this->price = $price;
        return $this;
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
        return self::create_items($dbh, $stmt);
    }
    static function get_item(PDO $dbh, int $id) : Item{
        $stmt = $dbh->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute(array($id));

        $item = $stmt->fetch();
        $new_item = new Item($dbh, $item['id']);

        return $new_item->setBrand($item['brand'])
            ->setCondition($item['condition'])
            ->setSize($item['size'])
            ->setModel($item['model'])
            ->setCategory($item['category'])
            ->setName($item['name'])
            ->setDescription($item['description'])
            ->setPrice($item['price'])
            ->setUser($item['user'])
            ->setDate($item['date'])
            ->setQuantity($item['quantity']);

    }
    static function get_user_items(PDO $dbh, string $username): array {
        $stmt = $dbh->prepare('SELECT * FROM items WHERE user = ?');
        $stmt->execute(array($username));
        return self::create_items($dbh, $stmt);
    }
    static function register_item(PDO $db, string $name, string $description, string $price, string $category, string $user) {
        $stmt = $db->prepare('INSERT INTO items (name, description, price, category, user) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $description, $price, $category, $user]);
    }
    static function register_item_images(PDO $db, array $images)
    {
        $stmt = $db->prepare('SELECT last_insert_rowid()');
        $stmt->execute();
        $id = $stmt->fetchColumn();
        foreach ($images as $image) {
            $stmt = $db->prepare('INSERT INTO item_images (item, imagePath) VALUES (?, ?)');
            $stmt->execute([$id, $image]);
        }
    }
}
