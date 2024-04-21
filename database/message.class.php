<?php
    declare(strict_types=1);
class Message {
    public int $chatroom;
    public int $sender;
    public int $sentTime;
    public ?int $readTime;
    public string $message;
    function __construct (int $chatroom, int $sender, int $sentTime, ?int $readTime, string $message) {
        $this->sender = $sender;
        $this->chatroom = $chatroom;
        $this->sentTime = $sentTime;
        $this->readTime = $readTime;
        $this->message = $message;
    }

    static function messages_get_users (PDO $dbh, int $userId) : array {
        $stmt = $dbh->prepare('SELECT * FROM messages WHERE sender = ? OR receiver = ?');
        $stmt->execute(array($userId, $userId));

        $users = []; //(user, lastMsg, unread count)
        while ($message = $stmt->fetch()) {
            $correspondent = (int)$message['sender'] === $userId ? (int)$message['receiver'] : (int)$message['sender'];

        }
//        $this->query(
//            "SELECT `user_from`, COUNT(*) `ur`
//      FROM `messages` WHERE `user_to`=? AND `date_read` IS NULL
//      GROUP BY `user_from`", [$for]);
//        while ($r = $this->stmt->fetch()) { $users[$r["user_from"]]["unread"] = $r["ur"]; }
//
//        return $users;
        return $users;
    }

    function read_messages (PDO $dbh, int $from, int $to, int $limit = 30) : array {
        $stmt = $dbh->prepare(
            'UPDATE messages SET readTime = ? WHERE sender = ? AND readTime IS NULL');
        $stmt->execute(array(time(), $from));

        $stmt = $dbh->prepare('SELECT * FROM messages
                                      WHERE sender IN (?,?) AND receiver IN (?,?)
                                      ORDER BY sentTime DESC
                                      LIMIT ?');
        $stmt->execute(array($from, $to, $from, $to, $limit));

        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message($message["sender"], $message["receiver"], $message["sentTime"], $message["readTime"]);
        }
        return $messages;
    }
    static function send_message (PDO $dbh, int $from, int $to, string $msg) : void {
        $stmt = $dbh->prepare(
            'INSERT INTO messages (sender, receiver, message, sentTime) VALUES (?,?,?,?)');
        $stmt->execute(array($from, $to, $msg, time()));
    }
}
