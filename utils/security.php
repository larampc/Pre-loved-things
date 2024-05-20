<?php

function generate_random_token(): string
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}

function validate_csrf_token(string $token): bool {
    if ($_SESSION['csrf'] !== $token) {
        $session = new Session();
        $session->add_message('error', 'Illegitimate request.');
        unset($_SESSION['csrf']);
        return false;
    }
    return true;
}
function validate_password(string $password): bool {
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)
        || !preg_match('/[?!@#$%^&*()\-+=\\/<>|{}.]/', $password) || strlen($password) < 8) {
        $session = new Session();
        $session->add_message('error', 'Invalid password - must be at least 8 characters long and contain at least one: uppercase and lowercase letter, digit, special character.');
        return false;
    }
    return true;
}