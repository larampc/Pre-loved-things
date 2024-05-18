<?php

declare(strict_types=1);

require_once('item.class.php');
require_once('../utils/uuid.php');
class User
{
    public string $user_id;
    public string $username;
    public string $name;
    public string $email;
    public string $phone;
    public string $image;
    public string $role;

    public function __construct(string $user_id, string $username, string $email, string $name, string $image, string $phone, string $role)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->name = $name;
        $this->image = $image;
        $this->email = $email;
        $this->phone = $phone;
        $this->role = $role;
    }

    public static function register_user(PDO $dbh, string $password, string $username, string $email, string $name, string $phone, string $currency): ?User
    {
        $stmt = $dbh->prepare('INSERT INTO users(user_id, password, username, name, email, phone, currency) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute(array(generate_uuid(),password_hash($password, PASSWORD_DEFAULT), $username, $name, $email, $phone, $currency));
        return self::verify_user($dbh, $email, $password);
    }

    public static function verify_user(PDO $dbh, string $email, string $password): ?User
    {
        $stmt = $dbh->prepare('SELECT * FROM users WHERE email = ? OR username = ? COLLATE nocase');
        $stmt->execute(array($email, $email));
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])) return self::get_user($dbh, $user['user_id']);
        else return null;
    }

    public static function verify_email(PDO $dbh, string $email): bool {
        $stmt = $dbh->prepare('SELECT * FROM users WHERE email = ? COLLATE nocase');
        $stmt->execute(array($email));
        $row = $stmt->fetch();
        return !empty($row);
    }

    public static function verify_username(PDO $dbh, string $username): bool {
        $stmt = $dbh->prepare('SELECT * FROM users WHERE username = ? COLLATE nocase');
        $stmt->execute(array($username));
        $row = $stmt->fetch();
        return !empty($row);
    }


    public static function like_item(PDO $dbh, string $id, string $item): void
    {
        $stmt = $dbh->prepare('INSERT INTO favorites VALUES (?, ?)');
        $stmt->execute(array($id, $item));
    }

    public static function dislike_item(PDO $dbh, string $id, string $item): void
    {
        $stmt = $dbh->prepare('DELETE FROM favorites WHERE user = ? AND item = ?');
        $stmt->execute(array($id, $item));
    }

    public static function add_cart(PDO $dbh, string $id, string $item): void
    {
        $stmt = $dbh->prepare('INSERT INTO user_cart VALUES (?, ?)');
        $stmt->execute(array($id, $item));
    }

    public static function remove_cart(PDO $dbh, string $id, string $item): void
    {
        $stmt = $dbh->prepare('DELETE FROM user_cart WHERE user = ? AND item = ?');
        $stmt->execute(array($id, $item));
    }

    public static function get_user(PDO $dbh, string $id): ?User {
        $stmt = $dbh->prepare('SELECT * FROM users WHERE user_id = ?');
        $stmt->execute(array($id));
        $user = $stmt->fetch();
        if (!$user) return null;
        return new User($user['user_id'], $user['username'], $user['email'],$user['name'], $user['image'], $user['phone'], $user['role']);
    }

    public static function get_user_feedback(PDO $dbh, string $id): array {
        $stmt = $dbh->prepare('SELECT * FROM comments WHERE mainuser = ?');
        $stmt->execute(array($id));
        $comments = array();
        while ($comment = $stmt->fetch()) {
            $comments[] = new Comment($dbh, $comment['userc'], $comment['mainuser'], $comment['text'], $comment['date'], $comment['rating']);
        }
        return $comments;
    }

    public static function update_user(PDO $dbh, string $id, $username,$email, $phone, $name, $image): bool
    {
        $stmt = $dbh->prepare('UPDATE users SET email = ?, phone = ?, name = ?, image = ?, username = ? WHERE user_id = ?');
        return $stmt->execute(array($email, $phone, $name, $image, $username,$id));
    }

    public static function get_cart_items(PDO $dbh, string $user_id): array
    {
        $stmt = $dbh->prepare('SELECT *
        FROM items LEFT JOIN user_cart 
        ON user_cart.item = items.id  WHERE user_cart.user = ?');
        $stmt->execute(array($user_id));
        return Item::create_items($dbh, $stmt->fetchAll());
    }

    static function get_cart_items_ids(PDO $dbh, string $user_id): array
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

    public static function add_to_cart(PDO $dbh, array $item_id, string $user): void
    {
        $in_cart = self::get_cart_items_ids($dbh, $user);
        foreach ($item_id as $id) {
            if (!(!empty($in_cart) && in_array($id, $in_cart)) && Item::get_item($dbh, $id)->creator->user_id !== $user) {
                $stmt = $dbh->prepare('INSERT INTO user_cart VALUES (?, ?)');
                $stmt->execute(array($user, $id));
            }
        }
    }

    public static function get_cart_items_from_user(PDO $dbh, string $buyer, string $seller): array {
        $stmt = $dbh->prepare('SELECT *
        FROM items LEFT JOIN user_cart 
        ON user_cart.item = items.id  WHERE user_cart.user = ? and items.creator = ?');
        $stmt->execute(array($buyer, $seller));
        return Item::create_items($dbh, $stmt->fetchAll());
    }

    public static function delete_user(PDO $dbh, string $user): bool
    {
        $stmt = $dbh->prepare('DELETE FROM users WHERE user_id = ?');
        if (!$stmt->execute(array($user))) return false;
        $stmt = $dbh->prepare('DELETE FROM comments WHERE mainuser = ? OR userc = ?');
        if (!$stmt->execute(array($user, $user))) return false;
        $stmt = $dbh->prepare('SELECT items.id FROM items WHERE creator = ?');
        $stmt->execute(array($user));
        $items = $stmt->fetchAll();
        foreach ($items as $item) {
            Item::delete_item($dbh, $item['id']);
        }
        return true;
    }
    public static function change_role(PDO $dbh, string $id, string $role): bool {
        $stmt = $dbh->prepare('UPDATE users SET role = ? WHERE user_id = ?');
        return $stmt->execute(array($role, $id));
    }

    public static function get_currency(PDO $dbh, string $user): string {
        $stmt = $dbh->prepare('SELECT currency FROM users WHERE user_id = ?');
        $stmt->execute(array($user));
        return $stmt->fetch()['currency'];
    }

    public static function set_user_currency(PDO $dbh, string $user_id, string $currency): bool {
        $stmt = $dbh->prepare('UPDATE users SET currency = ? WHERE user_id = ?');
        return $stmt->execute(array($currency, $user_id));
    }
    public static function get_currencies(PDO $dbh): array {
        $stmt = $dbh->prepare('SELECT * FROM currency');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function get_currency_conversion(PDO $dbh, string $currency) : float {
        $stmt = $dbh->prepare('SELECT value FROM currency WHERE code = ?');
        $stmt->execute(array($currency));
        return $stmt->fetch()['value'];
    }
    public static function get_currency_symbol(PDO $dbh, string $currency) : string {
        $stmt = $dbh->prepare('SELECT symbol FROM currency WHERE code = ?');
        $stmt->execute(array($currency));
        return $stmt->fetch()['symbol'];
    }

    public static function get_sold_items(PDO $dbh, string $user_id, string $month): int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items JOIN purchases on items.id = purchases.item JOIN purchaseData on purchases.purchase = purchaseData.id WHERE items.creator = ?  AND purchaseData.deliveryDate LIKE ?');
        $stmt->execute(array($user_id, "%/$month/".date("Y", time())));
        return $stmt->fetchColumn();
    }
    public static function get_bought_items(PDO $dbh, string $user_id, string $month): int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items JOIN purchases on items.id = purchases.item JOIN purchaseData on purchases.purchase = purchaseData.id WHERE purchaseData.buyer = ? AND purchaseData.deliveryDate LIKE ?');
        $stmt->execute(array($user_id, "%/$month/".date("Y", time())));
        return $stmt->fetchColumn();
    }
    public static function get_sold_user(PDO $dbh, string$user_id): int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items JOIN purchases on items.id = purchases.item WHERE items.creator = ? ');
        $stmt->execute(array($user_id));
        return $stmt->fetchColumn();
    }
    public static function get_bought_user(PDO $dbh, string$user_id): int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM items JOIN purchases on items.id = purchases.item JOIN purchaseData on purchases.purchase = purchaseData.id WHERE purchaseData.buyer = ? ');
        $stmt->execute(array($user_id));
        return $stmt->fetchColumn();
    }
    public static function get_users(PDO $dbh, int $page, string $search): array {
        $page = 3 * ($page - 1);
        $stmt = $dbh->prepare('SELECT * FROM users WHERE (username LIKE ?) OR (name LIKE ?) OR (email LIKE ?) LIMIT 3 offset ? ');
        $stmt->execute(array("%$search%","%$search%", "%$search%", $page));
        $users = $stmt->fetchAll();
        foreach ($users as &$user) {
            $user['sold'] = self::get_sold_user($dbh, $user['user_id']);
            $user['buy'] = self::get_bought_user($dbh, $user['user_id']);
        }
        return $users;
    }
}
