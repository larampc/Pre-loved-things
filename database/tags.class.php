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

    public static function get_item_category(PDO $dbh, int $item) {
        $stmt = $dbh->prepare('SELECT categories.category FROM tags_values join tags on tags.id = tags_values.tag join categories on categories.id = tags.category where tags_values.item = ?');
        $stmt->execute(array($item));
        return $stmt->fetchColumn() ?: "";
    }

    public static function get_item_tags(PDO $dbh, int $item):  array {
        $stmt = $dbh->prepare('SELECT tags.tag, tags_values.value FROM tags_values join tags on tags.id = tags_values.tag where tags_values.item = ?');
        $stmt->execute(array($item));
        return $stmt->fetchAll();
    }

    public static function get_category_tags(PDO $dbh, string $category):  array {
        $stmt = $dbh->prepare('SELECT tags.tag FROM tags join categories on tags.category = categories.id where categories.category = ?');
        $stmt->execute(array($category));
        return $stmt->fetchAll();
    }

    public static function get_tag_options(PDO $dbh, string $category, string $tag):  array {
        $stmt = $dbh->prepare('SELECT tags_predefined.value FROM tags join categories on tags.category = categories.id join tags_predefined on tags_predefined.tag = tags.id where categories.category = ? and tags.tag = ?');
        $stmt->execute(array($category, $tag));
        return $stmt->fetchAll();
    }

    public static function get_categories(PDO $dbh):  array {
        $stmt = $dbh->prepare('SELECT category FROM categories');
        $stmt->execute(array());
        return $stmt->fetchAll();
    }
}
