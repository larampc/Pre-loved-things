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

    static function read_messages (PDO $dbh, int $chatroom, int $sender, int $limit = 30) : array {
        $stmt = $dbh->prepare(
            'UPDATE messages SET readTime = ? WHERE chatroom = ? AND sender = ? AND readTime IS NULL');
        $stmt->execute(array(time(), $chatroom, $sender));
        $stmt = $dbh->prepare('SELECT * FROM messages
                                      WHERE chatroom = ? ORDER BY sentTime DESC
                                      LIMIT ?');
        $stmt->execute(array($chatroom, $limit));

        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Message($message["chatroom"], $message["sender"], $message["sentTime"], $message["readTime"], $message["message"]);
        }
        return $messages;
    }
    static function send_message (PDO $dbh, int $chatroom, int $sender, string $msg) : void {
        $stmt = $dbh->prepare(
            'INSERT INTO messages (chatroom, sender, sentTime, message) VALUES (?,?,?,?)');
        $stmt->execute(array($chatroom, $sender, time(), $msg));
    }
}
