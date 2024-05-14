<?php
declare(strict_types=1);
function log_to_stdout(string $string):void
{
    $out   = fopen('php://stdout', 'w');

    fputs($out, $string. "\n");
    fclose($out);

}
