<?php
  declare(strict_types = 1);

  function getItems(PDO $db) {
    $stmt = $db->prepare('SELECT * FROM items');
    $stmt->execute();
    return $stmt->fetchAll();
  }
?>