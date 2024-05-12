<?php
declare(strict_types=1);

require_once (__DIR__ . '/../database/item.class.php');
require_once (__DIR__ . '/../database/message.class.php');
require_once (__DIR__ . '/../utils/uuid.php');
class Chatroom {
    public string $chatroomId;
    public Item $item;
    public User $seller;
    public User $buyer;
    public int $unread_message_count;
    public ?Message $last_message;
    public function __construct(string $chatroom_id, Item $item, User $seller, User $buyer, int $unread_message_count, ?Message $last_message) {
        $this->chatroomId = $chatroom_id;
        $this->item = $item;
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->unread_message_count = $unread_message_count;
        $this->last_message = $last_message;
    }
    public static function save_chatroom(PDO $dbh, string $itemId, string $sellerId, string $buyerId) : Chatroom {
        $id = generate_uuid();
        $stmt = $dbh->prepare(
            'INSERT INTO chatrooms (chatroom_id,item_id, seller_id, buyer_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$id,$itemId, $sellerId, $buyerId]);
        return new Chatroom($id, Item::get_item($dbh, $itemId), User::get_user($dbh, $sellerId), User::get_user($dbh,$buyerId), 0, null);
    }
    public static function get_user_chatrooms(PDO $dbh, string $userId) : array {
        $stmt = $dbh->prepare('SELECT * FROM chatrooms WHERE buyer_id = ? OR seller_id = ?');
        $stmt->execute(array($userId, $userId));

        $chatrooms = array();
        while ($chatroom = $stmt->fetch()) {
            $chatrooms[] = new Chatroom($chatroom['chatroom_id'] ,Item::get_item($dbh, $chatroom['item_id']),
                User::get_user($dbh, $chatroom['seller_id']), User::get_user($dbh, $chatroom['buyer_id']),
                self::get_unread_message_count($dbh, $userId, $chatroom['chatroom_id']),
                self::get_last_message($dbh, $chatroom['chatroom_id']));
        }
        return $chatrooms;
    }
    public static function get_unread_message_count(PDO $dbh, string $user_id, string $chatroom_id) : int {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM messages WHERE chatroom = ? AND sender <> ? AND readTime is NULL');
        $stmt->execute([$chatroom_id, $user_id]);
        return $stmt->fetchColumn();
    }
    public static function get_last_message(PDO $dbh, string $chatroom_id) : ?Message {
        $stmt = $dbh->prepare('SELECT * FROM messages WHERE chatroom = ? ORDER BY sentTime DESC LIMIT 1');
        $stmt->execute([$chatroom_id]);
        $message = $stmt->fetch();
        if($message === false) return null;
        return new Message($chatroom_id , $message['sender'] , $message['sentTime'], $message['readTime'], $message['message']);
    }

    public static function get_chatroom_by_id(PDO $dbh, string $id, string $user) : Chatroom
    {
        $stmt = $dbh->prepare('SELECT * FROM chatrooms WHERE chatroom_id = ?');
        $stmt->execute([$id]);
        $chatroom = $stmt->fetch();
        return new Chatroom($chatroom['chatroom_id'] ,Item::get_item($dbh, $chatroom['item_id']), User::get_user($dbh, $chatroom['seller_id']),
        User::get_user($dbh, $chatroom['buyer_id']),
            self::get_unread_message_count($dbh, $user, $chatroom['chatroom_id']),
            self::get_last_message($dbh, $chatroom['chatroom_id']));
    }
}

