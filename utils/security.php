<?php

function generate_random_token(): string
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}

function validate_csrf_token(string $token): bool {
    if ($_SESSION['csrf'] !== $token) {
        $session = new Session();
        $session->addMessage('error', 'Illegitimate request.');
        unset($_SESSION['csrf']);
        return false;
    }
    return true;
}
function validate_password(string $password): bool {
    
}