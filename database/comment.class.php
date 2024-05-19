<?php

declare(strict_types=1);
require_once('../utils/uuid.php');

class Comment
{
    public User $from;
    public User $to;
    public string $message;
    public string $date;
    public int $rating;
    function __construct(PDO $dbh, $from, $to, $message, $date, $rating){
        $this->from = User::get_user($dbh, $from);
        $this->to = User::get_user($dbh, $to);
        $this->message = $message;
        $this->date = $date;
        $this->rating = intval($rating);
    }
    public static function add_review(PDO $dbh, string $user_id, string $id,string $review, int $rating) {
        $stmt = $dbh->prepare('INSERT INTO comments (id, mainuser, userc, text, rating, date) VALUES (?, ?,?,?,?,?)');
        $stmt->execute(array(generate_uuid(),$user_id, $id, $review, $rating, date('d/m/Y', time())));
    }
    public static function get_user_average(PDO $dbh, string $user_id) {
        $stmt = $dbh->prepare('SELECT AVG(rating) FROM comments WHERE mainuser = ?');
        $stmt->execute(array($user_id));
        return $stmt->fetchColumn();
    }
    public static function get_number_comments(PDO $dbh, string $user_id) {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM comments WHERE mainuser = ?');
        $stmt->execute(array($user_id));
        return $stmt->fetchColumn();
    }
}
