<?php

declare(strict_types=1);

class Currency
{
    public string $name;
    public float $conversion;
    public string $symbol;
    function __construct(PDO $dbh, string $name){
        $this->name = $name;
        $this->symbol = self::get_currency_symbol($dbh, $this->name);
        $this->conversion = self::get_currency_conversion($dbh, $this->name);
    }

    public static function get_currency_conversion(PDO $dbh, string $currency) : float {
        $stmt = $dbh->prepare('SELECT value FROM currency WHERE code = ?');
        $stmt->execute(array($currency));
        return $stmt->fetch()['value'];
    }
    public static function get_currency_symbol(PDO $dbh, string $currency) : string {
        $stmt = $dbh->prepare('SELECT symbol FROM currency WHERE code = ?');
        $stmt->execute(array($currency));
        return $stmt->fetch()['symbol'];
    }

}
