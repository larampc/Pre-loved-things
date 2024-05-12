<?php
class Session {
    private array $messages;

    public function __construct() {
        session_start();

        $this->messages = $_SESSION['messages'] ?? array();
        unset($_SESSION['messages']);
    }

    public function isLoggedIn() : bool {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
    }

    public function getId() : ?string {
        return $_SESSION['user_id'] ?? null;
    }

    public function setId(string $id) {
        $_SESSION['user_id'] = $id;
    }

    public function hasItemsCart(): bool {
        return isset($_SESSION['cart']);
    }

    public function addToCart(string $id) {
        $_SESSION['cart'][] = $id;
        $_SESSION['cart'] = array_unique($_SESSION['cart']);
    }

    public function getCart() : array {
        return $_SESSION['cart'] ?? array();
    }

    public function hasItemCheckout(): bool {
        return isset($_SESSION['user_items']);
    }

    public function getItemCheckout() : string {
        return $_SESSION['user_items'] ?? -1;
    }

    public function setItemCheckout($user_item) {
        $_SESSION['user_items'] = $user_item;
    }

    public function addMessage(string $type, string $text) {
        $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
        return $this->messages;
    }

    public function setCurrency(string $currency) {
        $_SESSION['currency'] = $currency;
    }

    public function getCurrency() : string {
        return $_SESSION['currency'] ?? 'EUR';
    }
}
?>