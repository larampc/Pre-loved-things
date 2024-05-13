<?php

declare(strict_types=1);

class Tag
{
    public string $tag;
    public string $value;

    public function __construct(string $tag, string $value) {
        $this->tag = $tag;
        $this->value = $value;
    }

    public static function get_category_by_id(PDO $dbh, string $id) {
        $stmt = $dbh->prepare('SELECT category FROM categories where category = ?');
        $stmt->execute(array($id));
        return $stmt->fetchColumn() ?: "";
    }

    public static function get_item_tags(PDO $dbh, string $item):  array {
        $stmt = $dbh->prepare('SELECT tags.tag, tags_values.data FROM tags_values join tags on tags.id = tags_values.tag where tags_values.item = ?');
        $stmt->execute(array($item));
        return $stmt->fetchAll();
    }

    public static function get_category_tags(PDO $dbh, string $category):  array {
        $stmt = $dbh->prepare('SELECT tags.tag, tags.id FROM tags where category = ?');
        $stmt->execute(array($category));
        return $stmt->fetchAll();
    }

    public static function get_tag_options(PDO $dbh, string $category, string $tag):  array {
        $stmt = $dbh->prepare('SELECT tags_predefined.value FROM tags join tags_predefined on tags_predefined.tag = tags.id where tags.category = ? and tags.tag = ?');
        $stmt->execute(array($category, $tag));
        return $stmt->fetchAll();
    }

    public static function get_categories(PDO $dbh):  array {
        $stmt = $dbh->prepare('SELECT category FROM categories');
        $stmt->execute(array());
        return $stmt->fetchAll();
    }

    public static function get_tag_id(PDO $dbh, string $category, string $tag):  mixed {
        $stmt = $dbh->prepare('SELECT id FROM tags where category = ? and tag = ?');
        $stmt->execute(array($category, $tag));
        return $stmt->fetchColumn();
    }

    static function register_item_tags(PDO $db, string $tag_id, string $item, string $value) {
        $stmt = $db->prepare('INSERT INTO tags_values (item, tag, data) VALUES (?, ?, ?)');
        $stmt->execute(array($item, $tag_id, $value));
    }

    static function get_items_with_tags(PDO $dbh, string $tag, string $value):  array
    {
        $stmt = $dbh->prepare('SELECT item FROM tags_values WHERE data LIKE ? and tag = ?');
        $stmt->execute(array("$value%", $tag));
        $res = $stmt->fetchAll();
        $items = array();
        foreach ($res as $item) {
            $items[] = $item['item'];
        }
        return $items;
    }

    static function add_category(PDO $dbh, string $category)
    {
        $stmt = $dbh->prepare('INSERT INTO categories (category) VALUES (?)');
        $stmt->execute(array($category));
        return $category;
    }
    static function add_tags_category(PDO $dbh, string $category_id, array $tags) {
        foreach ($tags as $tag) {
            $stmt = $dbh->prepare('INSERT INTO tags (id, category, tag) VALUES (?, ?)');
            $stmt->execute(array(generate_uuid(),$category_id, $tag));
        }
    }
    static function add_tag_options(PDO $dbh, array $options, string $tag) {
        foreach ($options as $option) {
            $stmt = $dbh->prepare('INSERT INTO tags_predefined (tag, value) VALUES (?, ?)');
            $stmt->execute(array($tag, $option));
        }
    }

    static function update_category(PDO $dbh, string $id, string $category)
    {
        $stmt = $dbh->prepare('UPDATE categories SET category = ? WHERE category = ?');
        $stmt->execute(array($category, $id));
    }

    static function delete_category_tags(PDO $dbh, string $id) {
        $stmt = $dbh->prepare('SELECT id FROM tags where category = ?');
        $stmt->execute(array($id));
        $tags = $stmt->fetchAll();
        foreach ($tags as $tag) {
            $stmt = $dbh->prepare('DELETE FROM tags_values where tag = ?');
            $stmt->execute(array($tag));
            $stmt = $dbh->prepare('DELETE FROM tags_predefined where tag = ?');
            $stmt->execute(array($tag));
        }
        $stmt = $dbh->prepare('DELETE FROM tags where category = ?');
        $stmt->execute(array($id));
    }
    static function get_tag_value_item(PDO $dbh, string $tag_id, string $item_id) {
        $stmt = $dbh->prepare('SELECT data FROM tags_values where tag = ? and item = ?');
        $stmt->execute(array($tag_id, $item_id));
        return $stmt->fetchColumn() ? :"";
    }

    static function remove_item_tags(PDO $dbh, string $item_id) {
        $stmt = $dbh->prepare('DELETE FROM tags_values where item = ?');
        $stmt->execute(array($item_id));
    }
    static function check_category(PDO $dbh, string $category): bool {
        $stmt = $dbh->prepare('SELECT category FROM categories where category = ?');
        $stmt->execute(array($category));
        $res = $stmt->fetchColumn();
        return isset($res);
    }
}
