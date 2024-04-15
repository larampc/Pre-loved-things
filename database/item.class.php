<?php
declare(strict_types = 1);

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
        $this->images = Item::get_item_images($db, $id);
    }

    public function get_main_image(): string
    {
        reset($this->images);
        return key($this->images);
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
    static function get_item_images(PDO $db, int $id) : array
    {
        var_dump($id);
        $stmt = $db->prepare('SELECT imagePath FROM item_images WHERE item = ? LIMIT 1');
        $stmt->execute();
        $images = $stmt->fetchAll();
        var_dump($images);
        return $images;
    }
    static function get_items(PDO $db, int $count) : array {
        $stmt = $db->prepare('SELECT * FROM items LIMIT ?');
        $stmt->execute(array($count));

        $items = array();
        while ($item = $stmt->fetch()) {
            $new_item = new Item($db, $item['id']);
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
    static function getItem(PDO $db, int $id) : Item{
        $stmt = $db->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute(array($id));

        $item = $stmt->fetch();
        $new_item = new Item($db, $item['id']);

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
}
