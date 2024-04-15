<?php
  declare(strict_types = 1);
  require_once('database/connection.db.php');

  function get_items(PDO $db, int $count) {
    $stmt = $db->prepare('SELECT * FROM items ORDER BY id DESC LIMIT ?');
    $stmt->execute([$count]);
    return $stmt->fetchAll();
  }

  function get_item(PDO $db, int $id) {
        $stmt = $db->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
  }

  function insert_item(PDO $db, string $name, string $description, string $price, string $category, string $user) {
      $stmt = $db->prepare('INSERT INTO items (name, description, price, category, user, imagePath) VALUES (?, ?, ?, ?, ?, "flower.png")');
      $stmt->execute([$name, $description, $price, $category, $user]);
  }
