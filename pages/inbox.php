<?php

    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: login.php?user='.$_GET["user_id"].'&item='.$_GET["item_id"]));
    if ($session->getId() === $_GET["user_id"]) die(header('Location: inbox.php'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/message.class.php');
    require_once(__DIR__ . '/../database/chatroom.class.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/chatroom.tpl.php');

    $dbh = get_database_connection();
    $chatrooms = Chatroom::get_user_chatrooms($dbh, $session->getId());
    usort($chatrooms, function ($a, $b) {
        return $a->last_message->sentTime < $b->last_message->sentTime;
    });
    get_header("inbox", $dbh, $session);
    draw_user_chatrooms($chatrooms, User::get_user($dbh, $session->getId()));
    $to = $_GET["user_id"];
    if(isset($to)){
        $to_chatroom = array_filter($chatrooms, function($chatroom) use($to) { return $chatroom->seller->user_id === $to && $chatroom->item->id === $_GET["item_id"]; });
        if(empty($to_chatroom)) {
            $item = $_GET["item_id"];
            draw_temporary_chatroom(User::get_user($dbh, $to), Item::get_item($dbh, $item));
        }
        else draw_big_chatroom($to_chatroom[0],User::get_user($dbh, $to), Message::read_messages($dbh, $to_chatroom[0]->chatroomId));
    }
    else {
        draw_empty_chatroom();
    }
    draw_footer();
