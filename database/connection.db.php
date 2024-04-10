
<?php 
function getDatabaseConnection(): PDO {
    return new PDO('sqlite:' . __DIR__ . '/preloved.db');
}

