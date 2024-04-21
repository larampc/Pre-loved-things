<?php

declare(strict_types=1);
require_once('database/connection.db.php');

function register_user(PDO $dbh, string $password, string $email, string $name, string $phone)
{
  $stmt = $dbh->prepare('INSERT INTO users(password, name, email, phone) VALUES (?, ?, ?, ?)');
  $stmt->execute(array(sha1($password), $name, $email, $phone));
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

function verify_user(PDO $dbh, string $email, string $password): array
{
  $stmt = $dbh->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
  $stmt->execute(array($email, sha1($password)));
  return $stmt->fetch();
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