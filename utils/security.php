<?php

function generate_random_token(): string
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}