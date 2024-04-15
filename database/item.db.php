<?php
  declare(strict_types = 1);

  function get_items(PDO $db, int $count) {
    $stmt = $db->prepare('SELECT * FROM items LIMIT ?');
    $stmt->execute([$count]);
    return $stmt->fetchAll();
  }

  function getItem(PDO $db, int $id) {
        $stmt = $db->prepare('SELECT * FROM items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
  }
