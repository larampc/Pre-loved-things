<?php

declare(strict_types=1);

function register_user(PDO $dbh, string $username, string $password, string $email)
{
  $stmt = $dbh->prepare('INSERT INTO users VALUES (?, ?, NULL, ?, NULL)');
  $stmt->execute(array($username, sha1($password), $email));
}

function verify_user(PDO $dbh, string $username, string $password): bool
{
  $stmt = $dbh->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
  $stmt->execute(array($username, sha1($password)));

  return $stmt->fetch() !== false;
}

function getUser(PDO $dbh, string $username): ?array {
    $stmt = $dbh->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute(array($username));
    return $stmt->fetch();
}

function getUserFeedback(PDO $dbh, string $username): ?array {
    $stmt = $dbh->prepare('SELECT * FROM comments WHERE mainuser = ?');
    $stmt->execute(array($username));
    return $stmt->fetchAll();
}

function getUserItems(PDO $dbh, string $username): ?array {
    $stmt = $dbh->prepare('SELECT * FROM items WHERE user = ?');
    $stmt->execute(array($username));
    return $stmt->fetchAll();
}