<?php

declare(strict_types=1);

class User
{
    public int $user_id;
    public string $name;
    public string $photoPath;

    public function __construct(int $user_id, string $name, string $photoPath)
    {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->photoPath = $photoPath;
    }

    public static function register_user(PDO $dbh, string $password, string $email, string $name, string $phone)
    {
        $stmt = $dbh->prepare('INSERT INTO users(password, name, email, phone) VALUES (?, ?, ?, ?)');
        $stmt->execute(array(sha1($password), $name, $email, $phone));
    }

    public static function like_item(PDO $dbh, int $id, int $item)
    {
        $stmt = $dbh->prepare('INSERT INTO favorites VALUES (?, ?)');
        $stmt->execute(array($id, $item));
    }

    public static function dislike_item(PDO $dbh, int $id, int $item)
    {
        $stmt = $dbh->prepare('DELETE FROM favorites WHERE user = ? AND item = ?');
        $stmt->execute(array($id, $item));
    }

    public static function add_cart(PDO $dbh, int $id, int $item)
    {
        $stmt = $dbh->prepare('INSERT INTO user_cart VALUES (?, ?)');
        $stmt->execute(array($id, $item));
    }

    public static function verify_user(PDO $dbh, string $email, string $password): array
    {
        $stmt = $dbh->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
        //$stmt->execute(array($email, sha1($password)));
        $stmt->execute(array($email, $password));
        return $stmt->fetch();
    }

    public static function get_user(PDO $dbh, int $id): User {
        $stmt = $dbh->prepare('SELECT * FROM users WHERE user_id = ?');
        $stmt->execute(array($id));
        $user = $stmt->fetch();
        return new User((int)$user['id'], $user['name'], $user['photoPath']);
    }

    public static function get_user_feedback(PDO $dbh, int $id): array {
        $stmt = $dbh->prepare('SELECT * FROM comments WHERE mainuser = ?');
        $stmt->execute(array($id));
        return $stmt->fetchAll();
    }

    public static function get_user_image(PDO $dbh, int $id): string {
        $stmt = $dbh->prepare('SELECT photoPath FROM users WHERE user_id = ?');
        $stmt->execute(array($id));
        return $stmt->fetchColumn();
    }

    public static function update_user(PDO $dbh, int $id, $email, $phone, $name, $photo) {
        $stmt = $dbh->prepare('UPDATE users SET email = ?, phone = ?, name = ?, photoPath=? WHERE user_id = ?');
        $stmt->execute(array( $email, $phone, $name, $photo, $id));
    }
}
