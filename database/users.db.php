<?php

declare(strict_types=1);
require_once('database/connection.db.php');

function register_user(PDO $dbh, string $username, string $password, string $email)
{
  $stmt = $dbh->prepare('INSERT INTO users(username, password, name, email, phone) VALUES (?, ?, NULL, ?, NULL)');
  $stmt->execute(array($username, sha1($password), $email));
}

function like_item(PDO $dbh, string $username, int $item)
{
    $stmt = $dbh->prepare('INSERT INTO favorites VALUES (?, ?)');
    $stmt->execute(array($username, $item));
}

function dislike_item(PDO $dbh, string $username, int $item)
{
    $stmt = $dbh->prepare('DELETE FROM favorites WHERE user = ? AND item = ?');
    $stmt->execute(array($username, $item));
}

function verify_user(PDO $dbh, string $username, string $password): bool
{
  $stmt = $dbh->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
  $stmt->execute(array($username, sha1($password)));

  return $stmt->fetch() !== false;
}

function get_user(PDO $dbh, string $username): array {
    $stmt = $dbh->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetch();
}

function get_user_feedback(PDO $dbh, string $username): array {
    $stmt = $dbh->prepare('SELECT * FROM comments WHERE mainuser = ?');
    $stmt->execute(array($username));
    return $stmt->fetchAll();
}

function get_user_image(PDO $dbh, string $username): string {
    $stmt = $dbh->prepare('SELECT photoPath FROM users WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetchColumn();
}