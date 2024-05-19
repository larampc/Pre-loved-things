<?php

require_once (__DIR__ . "/security.php");
class Session {
    private array $messages;

    public function __construct() {
        session_start();

        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = generate_random_token();
        }

        $this->messages = $_SESSION['messages'] ?? array();
        unset($_SESSION['messages']);
    }

    public function is_logged_in() : bool {
        return isset($_SESSION['user_id']);
    }

    public function logout(): void {
        session_destroy();
    }

    public function get_id() : ?string {
        return $_SESSION['user_id'] ?? null;
    }

    public function set_id(string $id): void {
        $_SESSION['user_id'] = $id;
    }
    public function is_admin(): bool {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function set_admin(): void {
        $_SESSION['user_role'] = 'admin';
    }
    public function has_items_cart(): bool {
        return isset($_SESSION['cart']);
    }

    public function add_to_cart(string $id): void {
        $_SESSION['cart'][] = $id;
        $_SESSION['cart'] = array_unique($_SESSION['cart']);
    }

    public function get_cart() : array {
        return $_SESSION['cart'] ?? array();
    }

    public function get_item_checkout() : string {
        return $_SESSION['user_items'] ?? -1;
    }

    public function set_item_checkout($user_item): void {
        $_SESSION['user_items'] = $user_item;
    }

    public function add_message(string $type, string $text): void {
        $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function get_messages() {
        return $this->messages;
    }

    public function set_currency(string $currency): void {
        $_SESSION['currency'] = $currency;
    }

    public function get_currency() : string {
        return $_SESSION['currency'] ?? 'EUR';
    }
}
?>