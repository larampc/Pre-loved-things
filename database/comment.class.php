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
}
