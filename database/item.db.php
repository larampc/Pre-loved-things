<?php
  declare(strict_types = 1);

  function get_items(PDO $db) {
    $stmt = $db->prepare('SELECT * FROM items LIMIT 5');
    $stmt->execute();
    return $stmt->fetchAll();
  }
