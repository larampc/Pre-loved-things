<?php
declare(strict_types=1);

session_start();

echo json_encode($_SESSION['user_id']);

