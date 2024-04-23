<?php

declare(strict_types=1);

function register_user(PDO $dbh, string $password, string $email, string $name, string $phone): int
{
  $stmt = $dbh->prepare('INSERT INTO users(password, name, email, phone) VALUES (?, ?, ?, ?)');
  $stmt->execute(array(sha1($password), $name, $email, $phone));
  return verify_user($dbh, $email, $password);
}

function verify_email(PDO $dbh, string $email): bool {
    $stmt = $dbh->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute(array($email));
    $row = $stmt->fetch();
    return !empty($row);
}

function like_item(PDO $dbh, int $id, int $item)
{
    $stmt = $dbh->prepare('INSERT INTO favorites VALUES (?, ?)');
    $stmt->execute(array($id, $item));
}

function dislike_item(PDO $dbh, int $id, int $item)
{
    $stmt = $dbh->prepare('DELETE FROM favorites WHERE user = ? AND item = ?');
    $stmt->execute(array($id, $item));
}

function add_cart(PDO $dbh, int $id, int $item)
{
    $stmt = $dbh->prepare('INSERT INTO user_cart VALUES (?, ?)');
    $stmt->execute(array($id, $item));
}

function remove_cart(PDO $dbh, int $id, int $item)
{
    $stmt = $dbh->prepare('DELETE FROM user_cart WHERE user = ? AND item = ?');
    $stmt->execute(array($id, $item));
}

function verify_user(PDO $dbh, string $email, string $password): int
{
  $stmt = $dbh->prepare('SELECT user_id FROM users WHERE email = ? AND password = ?');
  $stmt->execute(array($email, sha1($password)));
  $user = $stmt->fetchColumn();
  if ($user === false) return -1;
  return $user;
}

function get_user(PDO $dbh, int $id): array {
    $stmt = $dbh->prepare('SELECT * FROM users WHERE user_id = ?');
    $stmt->execute(array($id));
    return $stmt->fetch();
}

function get_user_feedback(PDO $dbh, int $id): array {
    $stmt = $dbh->prepare('SELECT * FROM comments WHERE mainuser = ?');
    $stmt->execute(array($id));
    return $stmt->fetchAll();
}

function get_user_image(PDO $dbh, int $id): string {
    $stmt = $dbh->prepare('SELECT photoPath FROM users WHERE user_id = ?');
    $stmt->execute(array($id));
    return $stmt->fetchColumn();
}

function update_user(PDO $dbh, int $id, $email, $phone, $name, $photo) {
    $stmt = $dbh->prepare('UPDATE users SET email = ?, phone = ?, name = ?, photoPath=? WHERE user_id = ?');
    $stmt->execute(array( $email, $phone, $name, $photo, $id));
}

function get_cart_items(PDO $dbh, int $user_id): array
{
    $stmt = $dbh->prepare('SELECT *
        FROM items LEFT JOIN user_cart 
        ON user_cart.item = items.id  WHERE user_cart.user = ?');
    $stmt->execute(array($user_id));
    return Item::create_items($dbh, $stmt->fetchAll());
}

function get_cart_items_ids(PDO $dbh, int $user_id): array
{
    $stmt = $dbh->prepare('SELECT items.id
        FROM items LEFT JOIN user_cart 
        ON user_cart.item = items.id  WHERE user_cart.user = ?');
    $stmt->execute(array($user_id));
    $items = $stmt->fetchAll();
    $ids = array();
    foreach ($items as $item) {
        $ids[] = $item['id'];
    }
    return $ids;
}

function add_to_cart(PDO $dbh, array $item_id, int $user) {
    $in_cart = get_cart_items_ids($dbh, $user);
    foreach ($item_id as $id) {
        if (!(!empty($in_cart) && in_array($id, $in_cart))) {
            $stmt = $dbh->prepare('INSERT INTO user_cart VALUES (?, ?)');
            $stmt->execute(array($user, $id));
        }
    }
}

function get_cart_items_from_user(PDO $dbh, int $buyer, int $seller): array {
    $stmt = $dbh->prepare('SELECT *
        FROM items LEFT JOIN user_cart 
        ON user_cart.item = items.id  WHERE user_cart.user = ? and items.creator = ?');
    $stmt->execute(array($buyer, $seller));
    return Item::create_items($dbh, $stmt->fetchAll());
}