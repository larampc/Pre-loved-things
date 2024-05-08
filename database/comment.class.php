<?php

declare(strict_types=1);

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
        $this->rating = $rating;
    }
    public static function add_review(PDO $dbh, int $user_id, int $id,string $review, int $rating) {
        $stmt = $dbh->prepare('INSERT INTO comments (mainuser, userc, text, rating, date) VALUES (?,?,?,?,?)');
        $stmt->execute(array($user_id, $id, $review, $rating, date('d/m/Y', time())));
    }
    public static function get_user_average(PDO $dbh, int $user_id) {
        $stmt = $dbh->prepare('SELECT AVG(rating) FROM comments WHERE mainuser = ?');
        $stmt->execute(array($user_id));
        return $stmt->fetchColumn();
    }
    public static function get_number_comments(PDO $dbh, int $user_id) {
        $stmt = $dbh->prepare('SELECT COUNT(*) FROM comments WHERE mainuser = ?');
        $stmt->execute(array($user_id));
        return $stmt->fetchColumn();
    }
}
